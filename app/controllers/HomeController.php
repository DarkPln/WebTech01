<?php

class HomeController extends Controller {
    public function index(): void {
        $carouselCars   = Car::getForCarousel();
        $carouselRepeat = empty($carouselCars) ? 0 : max(3, (int)ceil(18 / count($carouselCars)));
        $this->render('home/index', compact('carouselCars', 'carouselRepeat'));
    }
}
