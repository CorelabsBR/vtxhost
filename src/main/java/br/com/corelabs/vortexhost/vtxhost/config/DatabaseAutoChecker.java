package br.com.corelabs.vortexhost.vtxhost.config;

import java.lang.reflect.Field;
import java.sql.Connection;
import java.sql.DatabaseMetaData;
import java.sql.ResultSet;
import java.util.Set;

import javax.sql.DataSource;

import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;

import jakarta.persistence.Column;
import jakarta.persistence.EntityManager;
import jakarta.persistence.Table;
import jakarta.persistence.metamodel.EntityType;

@Component
public class DatabaseAutoChecker implements CommandLineRunner {

    private final EntityManager entityManager;
    private final DataSource dataSource;

    public DatabaseAutoChecker(EntityManager entityManager, DataSource dataSource) {
        this.entityManager = entityManager;
        this.dataSource = dataSource;
    }

    @Override
    public void run(String... args) throws Exception {

        System.out.println("🔍 Verificando banco automaticamente...");

        int diferencas = 0;

        Set<EntityType<?>> entidades = entityManager.getMetamodel().getEntities();

        try (Connection conn = dataSource.getConnection()) {

            DatabaseMetaData meta = conn.getMetaData();

            for (EntityType<?> entidade : entidades) {

                Class<?> clazz = entidade.getJavaType();

                // =========================
                // 📦 NOME DA TABELA
                // =========================
                String nomeTabela;

                if (clazz.isAnnotationPresent(Table.class)) {
                    Table table = clazz.getAnnotation(Table.class);
                    nomeTabela = table.name();
                } else {
                    nomeTabela = toSnakeCase(clazz.getSimpleName());
                }

                // =========================
                // 🔍 VERIFICA TABELA
                // =========================
                ResultSet tabela = meta.getTables(null, null, nomeTabela, null);

                if (!tabela.next()) {
                    diferencas++;
                    System.out.println("⚠ Tabela faltando: " + nomeTabela);
                    continue;
                }

                // =========================
                // 🔍 VERIFICA COLUNAS
                // =========================
                for (var attr : entidade.getAttributes()) {

                    try {

                        // ❌ IGNORA RELACIONAMENTOS
                        if (attr.isAssociation()) {
                            continue;
                        }

                        String nomeColuna;

                        try {
                            Field field = clazz.getDeclaredField(attr.getName());

                            // ✔ se tiver @Column usa ele
                            if (field.isAnnotationPresent(Column.class)) {
                                Column column = field.getAnnotation(Column.class);

                                if (!column.name().isBlank()) {
                                    nomeColuna = column.name();
                                } else {
                                    nomeColuna = toSnakeCase(attr.getName());
                                }

                            } else {
                                nomeColuna = toSnakeCase(attr.getName());
                            }

                        } catch (NoSuchFieldException e) {
                            nomeColuna = toSnakeCase(attr.getName());
                        }

                        ResultSet coluna = meta.getColumns(null, null, nomeTabela, nomeColuna);

                        if (!coluna.next()) {
                            diferencas++;
                            System.out.println("⚠ Coluna faltando: " + nomeColuna + " em " + nomeTabela);
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }
        }

        // =========================
        // 📊 RESULTADO FINAL
        // =========================
        if (diferencas > 0) {
            System.out.println("📦 Banco atualizado automaticamente!");
        } else {
            System.out.println("✔ Banco tudo certo!");
        }
    }

    // =========================
    // 🔧 CONVERSOR camelCase → snake_case
    // =========================
    private String toSnakeCase(String text) {
        return text
                .replaceAll("([a-z])([A-Z])", "$1_$2")
                .toLowerCase();
    }
}