---
name: Serene Academic Glassmorphism
colors:
  surface: '#f9f9f9'
  surface-dim: '#dadada'
  surface-bright: '#f9f9f9'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3f4'
  surface-container: '#eeeeee'
  surface-container-high: '#e8e8e8'
  surface-container-highest: '#e2e2e2'
  on-surface: '#1a1c1c'
  on-surface-variant: '#434655'
  inverse-surface: '#2f3131'
  inverse-on-surface: '#f0f1f1'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#576065'
  on-secondary: '#ffffff'
  secondary-container: '#dbe4ea'
  on-secondary-container: '#5d666b'
  tertiary: '#4d556b'
  on-tertiary: '#ffffff'
  tertiary-container: '#656d84'
  on-tertiary-container: '#eef0ff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#dbe4ea'
  secondary-fixed-dim: '#bfc8ce'
  on-secondary-fixed: '#141d21'
  on-secondary-fixed-variant: '#3f484d'
  tertiary-fixed: '#dae2fd'
  tertiary-fixed-dim: '#bec6e0'
  on-tertiary-fixed: '#131b2e'
  on-tertiary-fixed-variant: '#3f465c'
  background: '#f9f9f9'
  on-background: '#1a1c1c'
  surface-variant: '#e2e2e2'
typography:
  display-lg:
    fontFamily: Lexend
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Lexend
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  title-sm:
    fontFamily: Manrope
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
  body-md:
    fontFamily: Manrope
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-caps:
    fontFamily: Manrope
    fontSize: 12px
    fontWeight: '700'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 40px
  xl: 64px
  gutter: 24px
  margin: 32px
---

## Brand & Style
The brand personality is rooted in "Digital Serenity"—balancing the disciplined structure of an academic environment with the peaceful, reflective nature of Islamic principles. The target audience includes educators, parents, and students who require a focused, distraction-free monitoring tool.

This design system utilizes a **refined Glassmorphism** style. It departs from the typical "vibrant" glass look in favor of a "frosted sanctuary" aesthetic. By using subtle transparency and soft blurs, the UI evokes a sense of openness and honesty. The emotional response is one of calm authority, clarity, and trust, ensuring that data monitoring feels supportive rather than intrusive.

## Colors
The palette is intentionally restricted to shades of blue and white to maintain a scholarly and tranquil atmosphere. 

- **Primary Blue (#2563EB):** Represents focus and institutional reliability. Used for primary actions and progress indicators.
- **Surface White (#FFFFFF):** The base for glass layers, applied with varying levels of opacity.
- **Sky Tint (#F0F9FF):** Used for subtle backgrounds to prevent pure-white eye strain.
- **Deep Navy (#0F172A):** Reserved for high-contrast typography and essential iconography.

Semantic colors for "Success" should use a teal-leaning green, and "Warning" should use a soft amber, ensuring they harmonize with the cool blue base.

## Typography
The typography strategy prioritizes readability and professional warmth. **Lexend** is chosen for headlines due to its specific design for reading proficiency and its clean, modern geometric form that mirrors academic clarity. **Manrope** serves as the functional workhorse for body text and data, providing a balanced, professional, and trustworthy feel.

Maintain generous line heights to ensure a "breathable" layout. For Islamic calligraphy or quotes, use a slightly larger scale but maintain the Primary Blue color to keep it integrated into the system.

## Layout & Spacing
The layout follows a **fluid grid** model with significant breathing room to reflect the "Serenity" brand pillar. A 12-column system is used for desktop, scaling down to 4 columns for mobile. 

Spacing is based on an 8px linear scale. Emphasis should be placed on "Inner Padding" within glass containers (minimum 24px) to ensure content does not feel cramped against the translucent edges. Use large margins (32px+) between major sections to define a clear hierarchy without the need for heavy dividers.

## Elevation & Depth
Depth is achieved through **Backdrop Blurs** and **Ambient Shadows** rather than traditional Z-index stacking.

1.  **Base Layer:** A soft gradient background (White to Sky Tint).
2.  **Glass Layer:** White background with 60-80% opacity and a 16px-24px backdrop-filter blur. 
3.  **Border Treatment:** A 1.5px semi-transparent white border (inner glow effect) to define the edge of the glass.
4.  **Shadows:** Shadows are highly diffused (Blur 30px+) with low opacity (5-10%) and a slight blue tint (`#2563EB` at 5% opacity) to make elements appear as if they are floating on a cloud of light.

## Shapes
The shape language is "Approachable Geometric." Standard components utilize a 0.5rem (8px) radius. Larger containers and cards use a 1.5rem (24px) radius to create a soft, friendly silhouette. 

Incorporate subtle geometric patterns (such as simplified 8-point stars or lattice textures) as low-opacity overlays within the glass layers to subtly nod to the Islamic academic environment without distracting from the data.

## Components
- **Glass Cards:** The primary container. Use 70% opacity white with a 20px blur and a 1px white border at 40% opacity.
- **Buttons:** 
    - *Primary:* Solid Primary Blue with a soft blue glow shadow. 
    - *Secondary:* Glass style with a 2px Primary Blue border.
- **Input Fields:** Recessed appearance using a subtle inner shadow and 40% opacity white background. Focus states should transition to a solid Primary Blue border.
- **Progress Trackers:** Smooth, rounded bars using a "filled" blue against a "hollow" glass track.
- **Chips/Tags:** Pill-shaped, high-transparency blue background with Deep Navy text for category filtering (e.g., "Prayer Times," "Grade Alpha").
- **Academic Widgets:** Specialized components for "Student Attendance" and "Behavior Logs" should use iconography that is minimal and dignified.