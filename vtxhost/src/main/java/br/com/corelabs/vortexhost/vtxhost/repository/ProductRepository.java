package br.com.corelabs.vortexhost.vtxhost.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import br.com.corelabs.vortexhost.vtxhost.entity.Product;
import java.util.List;

@Repository
public interface ProductRepository extends JpaRepository<Product, Long> {
    List<Product> findByCategoriaId(Long categoriaId);
    List<Product> findByJogoId(Long jogoId);
    List<Product> findByLocalId(Long localId);
    List<Product> findByEgg(String egg);
}
