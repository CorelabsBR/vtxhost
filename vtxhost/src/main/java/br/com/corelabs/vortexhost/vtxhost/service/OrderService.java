package br.com.corelabs.vortexhost.vtxhost.service;

import br.com.corelabs.vortexhost.vtxhost.entity.Order;
import br.com.corelabs.vortexhost.vtxhost.entity.OrderItem;
import br.com.corelabs.vortexhost.vtxhost.entity.CartItem;
import br.com.corelabs.vortexhost.vtxhost.repository.OrderRepository;
import br.com.corelabs.vortexhost.vtxhost.repository.OrderItemRepository;
import br.com.corelabs.vortexhost.vtxhost.repository.CartItemRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.math.BigDecimal;
import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional
public class OrderService {

    private final OrderRepository orderRepository;
    private final OrderItemRepository orderItemRepository;
    private final CartItemRepository cartItemRepository;

    public Order criarPedidoDoCarrinho(Long userId) {
        List<CartItem> carrinho = cartItemRepository.findByUserId(userId);

        if (carrinho.isEmpty()) {
            throw new IllegalArgumentException("Carrinho vazio");
        }

        BigDecimal total = BigDecimal.ZERO;
        Order order = Order.builder()
                .userId(userId)
                .status(Order.StatusPedido.pendente)
                .total(total)
                .build();

        Order pedidoSalvo = orderRepository.save(order);

        for (CartItem cartItem : carrinho) {
            OrderItem orderItem = OrderItem.builder()
                    .orderId(pedidoSalvo.getId())
                    .productId(cartItem.getProductId())
                    .quantidade(cartItem.getQuantidade())
                    .preco(cartItem.getProduct().getPreco())
                    .build();
            orderItemRepository.save(orderItem);
        }

        cartItemRepository.deleteByUserId(userId);

        return pedidoSalvo;
    }

    public List<Order> obterPedidosPorUsuario(Long userId) {
        return orderRepository.findByUserId(userId);
    }

    public Order findById(Long id) {
        return orderRepository.findById(id).orElse(null);
    }

    @Transactional
    public Order atualizarStatus(Long orderId, Order.StatusPedido status) {
        Order order = orderRepository.findById(orderId)
                .orElseThrow(() -> new IllegalArgumentException("Pedido não encontrado"));
        order.setStatus(status);
        return orderRepository.save(order);
    }
}
