package br.com.corelabs.vortexhost.vtxhost.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import br.com.corelabs.vortexhost.vtxhost.entity.Location;
import java.util.List;

@Repository
public interface LocationRepository extends JpaRepository<Location, Long> {
    List<Location> findByAtivo(Boolean ativo);
}
