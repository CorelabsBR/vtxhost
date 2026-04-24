package br.com.corelabs.vortexhost.vtxhost.controller;

import java.util.Map;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import br.com.corelabs.vortexhost.vtxhost.entity.User;
import br.com.corelabs.vortexhost.vtxhost.service.UserService;

@RestController
@RequestMapping("/api/cadastro")
public class CadastroApiController {

    private final UserService userService;

    public CadastroApiController(UserService userService) {
        this.userService = userService;
    }

    @PostMapping
    public ResponseEntity<Map<String, Object>> cadastrar(@RequestBody User user) {
        try {
            user.setTipoUsuario(User.TipoUsuario.cliente);
            userService.cadastrarUsuario(user);
            return ResponseEntity.ok(Map.of(
                "success", true,
                "message", "Conta criada com sucesso"
            ));
        } catch (IllegalArgumentException ex) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(Map.of(
                "success", false,
                "message", ex.getMessage()
            ));
        }
    }
}
