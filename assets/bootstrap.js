import { startStimulusApp } from '@symfony/stimulus-bridge';

// Démarrer l'application Stimulus
const app = startStimulusApp(require.context(
    './controllers',    // Chemin vers vos contrôleurs Stimulus
    true,
    /\.(j|t)sx?$/
));