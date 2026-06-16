<?php
//Niclas: Carousel
class HomeController extends Controller {
    public function index(): void {
        //holt Fahrzeuge über Model aus DB
        $carouselCars   = Car::getForCarousel();
        //Wie oft muss Carousel wiederholt werden, damit immer 18 Fahrzeuge angezeigt werden? (mindestens 3 Wiederholungen, damit es nicht zu leer aussieht)
        $carouselRepeat = empty($carouselCars) ? 0 : max(3, (int)ceil(18 / count($carouselCars)));
        $this->render('home/index', compact('carouselCars', 'carouselRepeat'));
        //compact() erstellt ein Array aus den übergebenen Variablen, damit sie in der View (home/index.php) verfügbar sind    
    }
}
