import React, { useEffect, useState } from "react";
import { getTareas, eliminarTarea, crearTarea, actualizarTarea } from "../servicios/servicios";
import Swal from "sweetalert2";

const Tareas = () => {
    const [tareas, setTareas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [titulo, setTitulo] = useState("");
    const [descripcion, setDescripcion] = useState("");
    const [estado, setEstado] = useState("pendiente");
    const [tareaSeleccionada, setTareaSeleccionada] = useState(null);

    useEffect(() => {
        cargarTareas();
    }, []);

    const cargarTareas = async () => {
        try {
            const data = await getTareas();
            setTareas(data);
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };


    const Crear = async (e) => {
        e.preventDefault();
        if (!titulo || !descripcion) {
            Swal.fire({
                icon: "warning",
                title: "Campos vacíos",
                text: "Debes llenar todos los campos",
            });
            return;
        }
        try {
            const respuesta = await crearTarea({ titulo, descripcion, estado });
            if (!respuesta.error) {
                Swal.fire({
                    icon: "success",
                    title: "Tarea creada",
                    text: respuesta.message,
                    timer: 1500,
                    showConfirmButton: false,
                });
                setTitulo("");
                setDescripcion("");
                setEstado("pendiente");
                cargarTareas();
            } else {
                Swal.fire({ icon: "error", title: "Error", text: respuesta.message });
            }
        } catch (error) {
            Swal.fire({ icon: "error", title: "Error", text: "No se pudo crear la tarea" });
            console.error(error);
        }
    };

    const Eliminar = async (id) => {
        const confirm = await Swal.fire({
            title: "¿Eliminar tarea?",
            text: "No podrás revertir esto",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        });
        if (confirm.isConfirmed) {
            try {
                await eliminarTarea(id);
                Swal.fire("Eliminada!", "La tarea ha sido eliminada.", "success");
                cargarTareas();
            } catch (error) {
                Swal.fire("Error", "No se pudo eliminar la tarea", "error");
            }
        }
    };

    const abrirModal = (tarea) => {
        setTareaSeleccionada(tarea);
        const modal = new window.bootstrap.Modal(document.getElementById("editarTareaModal"));
        modal.show();
    };

    const GuardarEdicion = async () => {
        if (!tareaSeleccionada.titulo || !tareaSeleccionada.descripcion) {
            Swal.fire({ icon: "warning", title: "Campos vacíos", text: "Debes llenar todos los campos" });
            return;
        }
        try {
            const respuesta = await actualizarTarea(tareaSeleccionada.id, tareaSeleccionada);
            if (!respuesta.error) {
                Swal.fire({ icon: "success", title: "Tarea actualizada", text: respuesta.message, timer: 1500, showConfirmButton: false });
                cargarTareas();
                const modal = window.bootstrap.Modal.getInstance(document.getElementById("editarTareaModal"));
                modal.hide();
            } else {
                Swal.fire({ icon: "error", title: "Error", text: respuesta.message });
            }
        } catch (error) {
            Swal.fire({ icon: "error", title: "Error", text: "No se pudo actualizar la tarea" });
            console.error(error);
        }
    };

    if (loading) return <p className="text-center text-muted mt-5">Cargando tareas...</p>;

    return (
        <div className="container-fluid mt-5">

  
            <div className="card shadow-lg p-4 mb-5 border-0 rounded-4">
                <h5 className="text-center mb-4 text-primary fw-bold">📋 Crear Nueva Tarea</h5>
                <form className="row g-3" onSubmit={Crear}>
                    <div className="col-md-4">
                        <input
                            type="text"
                            className="form-control rounded-pill border-primary"
                            placeholder="Título"
                            value={titulo}
                            onChange={(e) => setTitulo(e.target.value)}
                        />
                    </div>
                    <div className="col-md-4">
                        <input
                            type="text"
                            className="form-control rounded-pill border-primary"
                            placeholder="Descripción"
                            value={descripcion}
                            onChange={(e) => setDescripcion(e.target.value)}
                        />
                    </div>
                    <div className="col-md-2">
                        <select
                            className="form-select rounded-pill border-primary"
                            value={estado}
                            onChange={(e) => setEstado(e.target.value)}
                        >
                            <option value="pendiente">Pendiente</option>
                            <option value="completada">Completada</option>
                        </select>
                    </div>
                    <div className="col-md-2 d-grid">
                        <button type="submit" className="btn btn-success btn-sm rounded-pill">
                            Crear Tarea
                        </button>
                    </div>
                </form>
            </div>

            {/* ===== CARDS DE TAREAS ===== */}
            <div className="row g-4">
                {tareas.map((tarea) => (
                    <div key={tarea.id} className="col-md-4">
                        <div className="card h-100 shadow-lg border-0 rounded-4 hover-shadow">
                            <div className="card-header bg-primary text-white fw-bold fs-5 rounded-top-4">
                                {tarea.titulo}
                            </div>
                            <div className="card-body d-flex flex-column">
                                <p className="card-text flex-grow-1 text-muted">{tarea.descripcion}</p>
                                <div className="d-flex justify-content-between align-items-center mt-3">
                                    <span className={`badge ${tarea.estado === "pendiente" ? "bg-warning text-dark" : "bg-success"}`}>
                                        {tarea.estado}
                                    </span>
                                    <div>
                                        <button
                                            className="btn btn-outline-primary btn-sm me-2"
                                            onClick={() => abrirModal(tarea)}
                                        >
                                            ✏️ Editar
                                        </button>
                                        <button
                                            className="btn btn-outline-danger btn-sm"
                                            onClick={() => Eliminar(tarea.id)}
                                        >
                                            🗑 Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* ===== MODAL EDITAR ===== */}
            <div className="modal fade" id="editarTareaModal" tabIndex="-1" aria-labelledby="editarTareaModalLabel" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered">
                    <div className="modal-content rounded-4 shadow-lg border-0">
                        <div className="modal-header bg-primary text-white rounded-top-4">
                            <h5 className="modal-title" id="editarTareaModalLabel">Editar Tarea</h5>
                            <button type="button" className="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body">
                            <div className="mb-3">
                                <label className="form-label fw-semibold">Título</label>
                                <input
                                    type="text"
                                    className="form-control rounded-pill border-primary"
                                    value={tareaSeleccionada?.titulo || ''}
                                    onChange={(e) => setTareaSeleccionada({ ...tareaSeleccionada, titulo: e.target.value })}
                                />
                            </div>
                            <div className="mb-3">
                                <label className="form-label fw-semibold">Descripción</label>
                                <textarea
                                    className="form-control rounded-3 border-primary"
                                    rows="3"
                                    value={tareaSeleccionada?.descripcion || ''}
                                    onChange={(e) => setTareaSeleccionada({ ...tareaSeleccionada, descripcion: e.target.value })}
                                ></textarea>
                            </div>
                            <div className="mb-3">
                                <label className="form-label fw-semibold">Estado</label>
                                <select
                                    className="form-select rounded-pill border-primary"
                                    value={tareaSeleccionada?.estado || 'pendiente'}
                                    onChange={(e) => setTareaSeleccionada({ ...tareaSeleccionada, estado: e.target.value })}
                                >
                                    <option value="pendiente">Pendiente</option>
                                    <option value="completada">Completada</option>
                                </select>
                            </div>
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" className="btn btn-primary rounded-pill" onClick={GuardarEdicion}>Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>




        </div>
    );
};

export default Tareas;
