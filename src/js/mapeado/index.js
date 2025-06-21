import { Dropdown } from "bootstrap";
import Chart from "chart.js/auto";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { data } from "jquery";
import L from 'leaflet';

let map;

// Coordenadas de Guatemala 
const guatemalaCenter = [14.576163, -90.533786];

const pingIcon = L.divIcon({
    className: '',
    html: `<span class="ping-marker"></span>`,
    iconSize: [12, 12],
    iconAnchor: [6, 6]
});

const inicializarMapa = () => {
    try {
        map = L.map('mapaGuatemala').setView(guatemalaCenter, 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18,
            minZoom: 5
        }).addTo(map);

        // Configurar límites del mapa para Guatemala
        const guatemalaBounds = [
            [14.575643, -90.533774]
        ];
        map.setMaxBounds(guatemalaBounds);

        L.marker(guatemalaCenter, { icon: pingIcon })
            .addTo(map)
            .bindPopup('<b>Almacen</b>')
            .openPopup();



        console.log('Mapa de Guatemala cargado correctamente');

    } catch (error) {
        console.error('Error al inicializar mapa:', error);
    }
};

// Inicializar cuando cargue el DOM
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        inicializarMapa();
    }, 100);
});