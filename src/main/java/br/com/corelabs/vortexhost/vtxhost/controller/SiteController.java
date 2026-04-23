package br.com.corelabs.vortexhost.vtxhost.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class SiteController {
    @GetMapping("/site")
    public String site() {
        return "site";
    }
}
