package br.com.corelabs.vortexhost.vtxhost.service;

import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import br.com.corelabs.vortexhost.vtxhost.entity.User;
import br.com.corelabs.vortexhost.vtxhost.repository.UserRepository;
import lombok.RequiredArgsConstructor;

@Service
@RequiredArgsConstructor
@Transactional
public class UserService {

    private final UserRepository userRepository;
    private final PasswordEncoder passwordEncoder;

    public User cadastrarUsuario(User usuario) {
        if (userRepository.findByEmail(usuario.getEmail()) != null) {
            throw new IllegalArgumentException("Email já cadastrado");
        }
        usuario.setSenha(passwordEncoder.encode(usuario.getSenha()));
        return userRepository.save(usuario);
    }

    public User authenticate(User user) {
        User usuarioBanco = userRepository.findByEmail(user.getEmail());

        if (usuarioBanco == null || !passwordEncoder.matches(user.getSenha(), usuarioBanco.getSenha())) {
            return null;
        }

        return usuarioBanco;
    }

    public User findByEmail(String email) {
        return userRepository.findByEmail(email);
    }

    public User findById(Long id) {
        return userRepository.findById(id).orElse(null);
    }

    public User atualizar(User usuario) {
        return userRepository.save(usuario);
    }
}
