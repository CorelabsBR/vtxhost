package br.com.corelabs.vortexhost.vtxhost.controller;

import br.com.corelabs.vortexhost.vtxhost.entity.Product;
import br.com.corelabs.vortexhost.vtxhost.entity.Category;
import br.com.corelabs.vortexhost.vtxhost.entity.Game;
import br.com.corelabs.vortexhost.vtxhost.entity.Location;
import br.com.corelabs.vortexhost.vtxhost.service.ProductService;
import br.com.corelabs.vortexhost.vtxhost.service.CategoryService;
import br.com.corelabs.vortexhost.vtxhost.service.GameService;
import br.com.corelabs.vortexhost.vtxhost.service.LocationService;
import lombok.RequiredArgsConstructor;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import java.util.List;

@RestController
@RequestMapping("/api/products")
@RequiredArgsConstructor
public class ProductController {

    private final ProductService productService;
    private final CategoryService categoryService;
    private final GameService gameService;
    private final LocationService locationService;

    @GetMapping
    public ResponseEntity<List<Product>> listarProdutos(
            @RequestParam(required = false) Long categoriaId,
            @RequestParam(required = false) Long jogoId,
            @RequestParam(required = false) Long localId,
            @RequestParam(required = false) String egg) {

        List<Product> produtos;

        if (categoriaId != null) {
            produtos = productService.findByCategoriaId(categoriaId);
        } else if (jogoId != null) {
            produtos = productService.findByJogoId(jogoId);
        } else if (localId != null) {
            produtos = productService.findByLocalId(localId);
        } else if (egg != null) {
            produtos = productService.findByEgg(egg);
        } else {
            produtos = productService.listarTodos();
        }

        return ResponseEntity.ok(produtos);
    }

    @GetMapping("/{id}")
    public ResponseEntity<Product> obterProduto(@PathVariable Long id) {
        Product produto = productService.findById(id);
        return produto != null ? ResponseEntity.ok(produto) : ResponseEntity.notFound().build();
    }

    @GetMapping("/categorias/todas")
    public ResponseEntity<List<Category>> listarCategorias() {
        return ResponseEntity.ok(categoryService.listarTodas());
    }

    @GetMapping("/jogos/todos")
    public ResponseEntity<List<Game>> listarJogos() {
        return ResponseEntity.ok(gameService.listarTodos());
    }

    @GetMapping("/localizacoes/todas")
    public ResponseEntity<List<Location>> listarLocalizacoes() {
        return ResponseEntity.ok(locationService.listarTodas());
    }
}
