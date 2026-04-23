package br.com.corelabs.vortexhost.vtxhost.service;

import br.com.corelabs.vortexhost.vtxhost.entity.Game;
import br.com.corelabs.vortexhost.vtxhost.repository.GameRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional(readOnly = true)
public class GameService {

    private final GameRepository gameRepository;

    public List<Game> listarTodos() {
        return gameRepository.findAll();
    }

    public List<Game> listarAtivos() {
        return gameRepository.findByAtivo(true);
    }

    public Game findById(Long id) {
        return gameRepository.findById(id).orElse(null);
    }

    @Transactional
    public Game salvar(Game game) {
        return gameRepository.save(game);
    }
}
