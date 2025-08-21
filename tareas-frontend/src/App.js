import React from "react";
import Tareas from "./componentes/Tareas";

function App() {
  return (
    <div className="min-h-screen bg-light">

      <nav className="navbar navbar-expand-lg navbar-dark bg-primary shadow-lg">
        <div className="container justify-content-center">
          <h1 className="navbar-brand mb-0 text-center">
            📋 Mis tareas
          </h1>
          <button
            className="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span className="navbar-toggler-icon"></span>
          </button>
        </div>
      </nav>



      <Tareas />
    </div>
  );
}

export default App;
