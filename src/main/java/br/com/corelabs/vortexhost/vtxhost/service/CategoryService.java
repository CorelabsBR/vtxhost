package br.com.corelabs.vortexhost.vtxhost.service;

import br.com.corelabs.vortexhost.vtxhost.entity.Category;
import br.com.corelabs.vortexhost.vtxhost.repository.CategoryRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional(readOnly = true)
public class CategoryService {

    private final CategoryRepository categoryRepository;

    public List<Category> listarTodas() {
        return categoryRepository.findAll();
    }

    public Category findById(Long id) {
        return categoryRepository.findById(id).orElse(null);
    }

    @Transactional
    public Category salvar(Category category) {
        return categoryRepository.save(category);
    }
}
