package br.com.corelabs.vortexhost.vtxhost.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.web.SecurityFilterChain;

@Configuration
public class SecurityConfig {

    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http) throws Exception {

        http
            // ✅ NÃO desativa CSRF
            .authorizeHttpRequests(auth -> auth
                .requestMatchers(
                    "/", 
                    "/home",
                    "/auth/**",
                    "/login",
                    "/api/cadastro",
                    "/css/**",
                    "/js/**",
                    "/images/**"
                ).permitAll()
                .requestMatchers("/areacliente/**").authenticated()
                .anyRequest().permitAll()
            )
            .formLogin(form -> form
                .loginPage("/login") 
                .permitAll()
            );

        return http.build();
    }
}