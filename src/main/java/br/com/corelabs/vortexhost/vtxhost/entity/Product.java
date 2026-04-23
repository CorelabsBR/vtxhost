package br.com.corelabs.vortexhost.vtxhost.entity;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;
import jakarta.persistence.*;
import java.math.BigDecimal;
import java.time.LocalDateTime;

@Entity
@Table(name = "products")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class Product {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String nome;

    private String descricao;

    @Column(nullable = false)
    private BigDecimal preco;

    @Column(name = "categoria_id", nullable = false)
    private Long categoriaId;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "categoria_id", insertable = false, updatable = false)
    private Category categoria;

    @Column(name = "jogo_id", nullable = false)
    private Long jogoId;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "jogo_id", insertable = false, updatable = false)
    private Game jogo;

    @Column(name = "local_id", nullable = false)
    private Long localId;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "local_id", insertable = false, updatable = false)
    private Location location;

    @Column(nullable = false)
    private String ram;

    @Column(nullable = false)
    private String cpu;

    @Column(nullable = false)
    private String storage;

    @Column(nullable = false)
    private Boolean ddosProtection;

    @Column(nullable = false)
    private Boolean featured;

    @Column(nullable = false)
    private Integer sortOrder;

    @Column(length = 3)
    private String egg;

    @Column(name = "criado_em", nullable = false, updatable = false)
    private LocalDateTime criadoEm;

    @Column(name = "atualizado_em")
    private LocalDateTime atualizadoEm;

    @PrePersist
    public void prePersist() {
        this.criadoEm = LocalDateTime.now();
        this.atualizadoEm = LocalDateTime.now();
    }

    @PreUpdate
    public void preUpdate() {
        this.atualizadoEm = LocalDateTime.now();
    }
}
