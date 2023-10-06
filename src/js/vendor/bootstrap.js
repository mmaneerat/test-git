// Import our custom CSS
import "./../../scss/style.scss";

// Import all of Bootstrap's JS
import * as bootstrap from "bootstrap";

const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);
