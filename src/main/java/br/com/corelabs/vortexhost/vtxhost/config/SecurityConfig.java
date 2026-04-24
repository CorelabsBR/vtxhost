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
            .csrf(csrf -> csrf.disable())
            .authorizeHttpRequests(auth -> auth
                // 🔓 público
                .requestMatchers(
                    "/", 
                    "/home",
                    "/auth/**",
                    "/login",
                    "/css/**",
                    "/js/**",
                    "/images/**"
                ).permitAll()

                // 🔒 protegido (área do cliente)
                .requestMatchers("/areacliente/**").authenticated()

                // 🔓 resto liberado (opcional)
                .anyRequest().permitAll()
            )
            .formLogin(form -> form
                .loginPage("/login") // sua página
                .permitAll()
            );

        return http.build();
    }
}