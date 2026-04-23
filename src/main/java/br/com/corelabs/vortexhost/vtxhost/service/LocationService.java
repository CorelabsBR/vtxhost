package br.com.corelabs.vortexhost.vtxhost.service;

import br.com.corelabs.vortexhost.vtxhost.entity.Location;
import br.com.corelabs.vortexhost.vtxhost.repository.LocationRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional(readOnly = true)
public class LocationService {

    private final LocationRepository locationRepository;

    public List<Location> listarTodas() {
        return locationRepository.findAll();
    }

    public List<Location> listarAtivas() {
        return locationRepository.findByAtivo(true);
    }

    public Location findById(Long id) {
        return locationRepository.findById(id).orElse(null);
    }

    @Transactional
    public Location salvar(Location location) {
        return locationRepository.save(location);
    }
}
