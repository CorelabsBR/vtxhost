package br.com.corelabs.vortexhost.vtxhost.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import br.com.corelabs.vortexhost.vtxhost.entity.Game;
import java.util.List;

@Repository
public interface GameRepository extends JpaRepository<Game, Long> {
    List<Game> findByAtivo(Boolean ativo);
}
