import { cn } from "../../lib/utils.js";

export function createCard(element, { className = "", ...props } = {}) {
  if (!element) return null;
  
  element.className = cn(
    "rounded-lg border bg-card text-card-foreground shadow-sm",
    className
  );
  
  Object.entries(props).forEach(([key, value]) => {
    if (key !== 'className') {
      element.setAttribute(key, value);
    }
  });
  
  return element;
}

export function createCardHeader(element, { className = "", ...props } = {}) {
  if (!element) return null;
  
  element.className = cn("flex flex-col space-y-1.5 p-6", className);
  
  Object.entries(props).forEach(([key, value]) => {
    if (key !== 'className') {
      element.setAttribute(key, value);
    }
  });
  
  return element;
}

export function createCardTitle(element, { className = "", ...props } = {}) {
  if (!element) return null;
  
  element.className = cn(
    "text-2xl font-semibold leading-none tracking-tight",
    className
  );
  
  Object.entries(props).forEach(([key, value]) => {
    if (key !== 'className') {
      element.setAttribute(key, value);
    }
  });
  
  return element;
}

export function createCardDescription(element, { className = "", ...props } = {}) {
  if (!element) return null;
  
  element.className = cn("text-sm text-muted-foreground", className);
  
  Object.entries(props).forEach(([key, value]) => {
    if (key !== 'className') {
      element.setAttribute(key, value);
    }
  });
  
  return element;
}

export function createCardContent(element, { className = "", ...props } = {}) {
  if (!element) return null;
  
  element.className = cn("p-6 pt-0", className);
  
  Object.entries(props).forEach(([key, value]) => {
    if (key !== 'className') {
      element.setAttribute(key, value);
    }
  });
  
  return element;
}

export function createCardFooter(element, { className = "", ...props } = {}) {
  if (!element) return null;
  
  element.className = cn("flex items-center p-6 pt-0", className);
  
  Object.entries(props).forEach(([key, value]) => {
    if (key !== 'className') {
      element.setAttribute(key, value);
    }
  });
  
  return element;
}