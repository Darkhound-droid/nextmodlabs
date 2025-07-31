# ToolHub - Professional Web Tools Platform

## Overview

ToolHub is a full-stack web application that provides professional-grade web tools including an advanced calculator, text converter, and image compressor. The application is built with a modern React frontend and Express.js backend, utilizing a PostgreSQL database with Drizzle ORM for data management.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Frontend Architecture
- **Framework**: React 18 with TypeScript
- **Routing**: Wouter for client-side routing
- **Styling**: Tailwind CSS with shadcn/ui component library
- **State Management**: React hooks with TanStack Query for server state
- **Build Tool**: Vite for development and production builds
- **Analytics**: Custom hook for database-backed tool usage tracking

### Backend Architecture
- **Framework**: Express.js with TypeScript
- **Runtime**: Node.js with ES modules
- **Database**: PostgreSQL with Neon serverless hosting
- **Database ORM**: Drizzle ORM with full CRUD operations
- **Storage Layer**: DatabaseStorage class implementing IStorage interface
- **API Endpoints**: RESTful routes for tool data persistence and analytics

### UI/UX Design System
- **Component Library**: shadcn/ui (Radix UI primitives)
- **Design System**: "New York" variant with neutral color scheme
- **Responsive Design**: Mobile-first approach with Tailwind breakpoints
- **Accessibility**: Built-in support through Radix UI components

## Key Components

### Frontend Components
- **Layout System**: Header, Footer, and main content areas
- **Tool-Specific Components**: 
  - Calculator with display, buttons, and history
  - Text converter with statistics and multiple operations
  - Image compressor with drag-and-drop interface
- **UI Components**: Comprehensive set of reusable components (buttons, cards, forms, etc.)

### Backend Services
- **Storage Interface**: Abstract storage layer with memory implementation
- **Route Registration**: Modular route system for API endpoints
- **Development Server**: Vite integration for hot reloading

### Database Schema
- **Users Table**: Basic user management with UUID primary keys
- **Schema Validation**: Zod integration for type-safe data validation

## Data Flow

### Client-Server Communication
- **API Pattern**: RESTful endpoints prefixed with `/api`
- **Data Fetching**: TanStack Query for caching and synchronization
- **Error Handling**: Centralized error boundaries and toast notifications

### State Management
- **Local State**: React hooks for component-specific state
- **Server State**: TanStack Query for API data caching
- **Form State**: React Hook Form with Zod validation

### Tool-Specific Data Flow
- **Calculator**: In-memory calculation history with expression parsing
- **Text Converter**: Real-time text transformation with statistics calculation
- **Image Compressor**: Client-side image processing with Canvas API

## External Dependencies

### Core Framework Dependencies
- **React Ecosystem**: React, React DOM, React Router (Wouter)
- **Backend**: Express.js, Node.js with TypeScript support
- **Database**: Neon PostgreSQL with Drizzle ORM

### UI and Styling
- **Component Library**: Radix UI primitives for accessibility
- **Styling**: Tailwind CSS with PostCSS processing
- **Icons**: Lucide React for consistent iconography

### Development Tools
- **Build Tools**: Vite, esbuild for production builds
- **Type Checking**: TypeScript with strict configuration
- **Development**: tsx for TypeScript execution, hot reloading

### Third-Party Services
- **Database Hosting**: Neon serverless PostgreSQL
- **Development Platform**: Replit integration with custom plugins

## Deployment Strategy

### Development Environment
- **Local Development**: Vite dev server with Express backend
- **Hot Reloading**: Full-stack hot reloading with Vite middleware
- **Type Checking**: Real-time TypeScript compilation

### Production Build
- **Frontend**: Vite production build with asset optimization
- **Backend**: esbuild compilation to ES modules
- **Database**: Drizzle migrations with push-based schema updates

### Environment Configuration
- **Database**: PostgreSQL connection via DATABASE_URL environment variable
- **Development**: NODE_ENV-based configuration switching
- **Asset Serving**: Static file serving for production builds

### Scalability Considerations
- **Database**: Serverless PostgreSQL with connection pooling
- **Storage**: Abstract storage interface allows for future database-backed implementations
- **Caching**: TanStack Query provides client-side caching layer