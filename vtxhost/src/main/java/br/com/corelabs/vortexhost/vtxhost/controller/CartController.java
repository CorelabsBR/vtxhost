package br.com.corelabs.vortexhost.vtxhost.controller;

import br.com.corelabs.vortexhost.vtxhost.entity.CartItem;
import br.com.corelabs.vortexhost.vtxhost.service.CartService;
import lombok.RequiredArgsConstructor;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api/cart")
@RequiredArgsConstructor
public class CartController {

    private final CartService cartService;

    @GetMapping
    public ResponseEntity<List<CartItem>> obterCarrinho(@RequestParam Long userId) {
        return ResponseEntity.ok(cartService.obterCarrinho(userId));
    }

    @PostMapping("/add")
    public ResponseEntity<?> adicionarAoCarrinho(
            @RequestParam Long userId,
            @RequestParam Long productId,
            @RequestParam(defaultValue = "1") Integer quantidade) {
        try {
            CartItem item = cartService.adicionarAoCarrinho(userId, productId, quantidade);
            Map<String, Object> response = new HashMap<>();
            response.put("sucesso", true);
            response.put("item", item);
            return ResponseEntity.ok(response);
        } catch (IllegalArgumentException e) {
            Map<String, Object> response = new HashMap<>();
            response.put("sucesso", false);
            response.put("mensagem", e.getMessage());
            return ResponseEntity.badRequest().body(response);
        }
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<?> removerDoCarrinho(@PathVariable Long id) {
        cartService.removerDoCarrinho(id);
        Map<String, Object> response = new HashMap<>();
        response.put("sucesso", true);
        response.put("mensagem", "Item removido do carrinho");
        return ResponseEntity.ok(response);
    }

    @DeleteMapping("/clear")
    public ResponseEntity<?> limparCarrinho(@RequestParam Long userId) {
        cartService.limparCarrinho(userId);
        Map<String, Object> response = new HashMap<>();
        response.put("sucesso", true);
        response.put("mensagem", "Carrinho limpado");
        return ResponseEntity.ok(response);
    }
}
