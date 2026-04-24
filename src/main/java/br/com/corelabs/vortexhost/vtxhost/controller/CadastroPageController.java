package br.com.corelabs.vortexhost.vtxhost.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;

import br.com.corelabs.vortexhost.vtxhost.entity.User;

@Controller
public class CadastroPageController {

    @ModelAttribute("user")
    public User user() {
        User user = new User();
        user.setPais("Brasil");
        return user;
    }

    @GetMapping({"/cadastro", "/registro"})
    public String cadastro() {
        return "cadastro";
    }
}
