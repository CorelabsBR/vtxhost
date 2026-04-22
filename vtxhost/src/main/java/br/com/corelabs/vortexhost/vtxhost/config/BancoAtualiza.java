package br.com.hunsriqueano.libelle.config;

import br.com.hunsriqueano.libelle.entity.VariacaoOrtografica;
import br.com.hunsriqueano.libelle.repository.VariacaoOrtograficaRepository;
import org.apache.commons.codec.language.ColognePhonetic;
import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;

import java.util.List;

@Component
public class BancoAtualiza implements CommandLineRunner {

    private final VariacaoOrtograficaRepository repository;
    private final ColognePhonetic algoritmoFonetico; // Usa o seu AppConfig!

    public BancoAtualiza(VariacaoOrtograficaRepository repository, ColognePhonetic algoritmoFonetico) {
        this.repository = repository;
        this.algoritmoFonetico = algoritmoFonetico;
    }

    @Override
    public void run(String... args) throws Exception {
        System.out.println("🔧 VERIFICANDO CÓDIGOS FONÉTICOS...");

        List<VariacaoOrtografica> todas = repository.findAll();
        int corrigidos = 0;

        for (VariacaoOrtografica vo : todas) {
            if (vo.getCodigoFonetico() == null || vo.getCodigoFonetico().isEmpty()) {

                String codigo = algoritmoFonetico.encode(vo.getGrafiaAlternativa());
                vo.setCodigoFonetico(codigo);

                repository.save(vo);
                corrigidos++;
            }
        }

        if (corrigidos > 0) {
            System.out.println("BANCO CONSERTADO! " + corrigidos + " palavras atualizadas.");
        } else {
            System.out.println("TUDO CERTO! O banco já estava atualizado.");
        }
    }
}