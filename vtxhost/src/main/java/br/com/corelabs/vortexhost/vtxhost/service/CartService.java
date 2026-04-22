package br.com.corelabs.vortexhost.vtxhost.service;

import br.com.corelabs.vortexhost.vtxhost.entity.CartItem;
import br.com.corelabs.vortexhost.vtxhost.entity.Product;
import br.com.corelabs.vortexhost.vtxhost.repository.CartItemRepository;
import br.com.corelabs.vortexhost.vtxhost.repository.ProductRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
@Transactional
public class CartService {

    private final CartItemRepository cartItemRepository;
    private final ProductRepository productRepository;

    public List<CartItem> obterCarrinho(Long userId) {
        return cartItemRepository.findByUserId(userId);
    }

    public CartItem adicionarAoCarrinho(Long userId, Long productId, Integer quantidade) {
        Product product = productRepository.findById(productId)
                .orElseThrow(() -> new IllegalArgumentException("Produto não encontrado"));

        Optional<CartItem> existente = cartItemRepository.findByUserIdAndProductId(userId, productId);

        if (existente.isPresent()) {
            CartItem item = existente.get();
            item.setQuantidade(item.getQuantidade() + quantidade);
            return cartItemRepository.save(item);
        }

        CartItem novoItem = CartItem.builder()
                .userId(userId)
                .productId(productId)
                .quantidade(quantidade)
                .build();
        return cartItemRepository.save(novoItem);
    }

    public void removerDoCarrinho(Long cartItemId) {
        cartItemRepository.deleteById(cartItemId);
    }

    public void limparCarrinho(Long userId) {
        cartItemRepository.deleteByUserId(userId);
    }

    public Integer obterQuantidadeTotal(Long userId) {
        return cartItemRepository.findByUserId(userId)
                .stream()
                .mapToInt(CartItem::getQuantidade)
                .sum();
    }
}
