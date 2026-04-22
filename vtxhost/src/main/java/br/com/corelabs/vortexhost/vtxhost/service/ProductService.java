package br.com.corelabs.vortexhost.vtxhost.service;

import br.com.corelabs.vortexhost.vtxhost.entity.Product;
import br.com.corelabs.vortexhost.vtxhost.repository.ProductRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional(readOnly = true)
public class ProductService {

    private final ProductRepository productRepository;

    public List<Product> listarTodos() {
        return productRepository.findAll();
    }

    public Product findById(Long id) {
        return productRepository.findById(id).orElse(null);
    }

    public List<Product> findByCategoriaId(Long categoriaId) {
        return productRepository.findByCategoriaId(categoriaId);
    }

    public List<Product> findByJogoId(Long jogoId) {
        return productRepository.findByJogoId(jogoId);
    }

    public List<Product> findByLocalId(Long localId) {
        return productRepository.findByLocalId(localId);
    }

    public List<Product> findByEgg(String egg) {
        return productRepository.findByEgg(egg);
    }

    @Transactional
    public Product salvar(Product product) {
        return productRepository.save(product);
    }
}
