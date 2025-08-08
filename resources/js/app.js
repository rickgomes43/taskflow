import './bootstrap';

// Import UI components
import { createButton } from './components/ui/button.js';
import { createCard, createCardHeader, createCardTitle, createCardDescription, createCardContent, createCardFooter } from './components/ui/card.js';

// Make components available globally for easy use in Blade templates
window.UI = {
  createButton,
  createCard,
  createCardHeader,
  createCardTitle,
  createCardDescription,
  createCardContent,
  createCardFooter
};
