import axios from "axios";

const API_URL = "http://localhost:8081/Api_solati/index.php/tasks";


export const getTareas = async () => {
  try {
    const res = await axios.get(API_URL);
    return res.data;
  } catch (error) {
    console.error("Error al obtener tareas:", error);
    throw error;
  }
};


export const crearTarea = async (tarea) => {
  try {
    const res = await axios.post(API_URL, tarea);
    return res.data;
  } catch (error) {
    console.error("Error al crear tarea:", error);
    throw error;
  }
};


export const actualizarTarea = async (id, tarea) => {
  try {
    const res = await axios.put(`${API_URL}/${id}`, tarea);
    return res.data;
  } catch (error) {
    console.error("Error al actualizar tarea:", error);
    throw error;
  }
};


export const eliminarTarea = async (id) => {
  try {
    const res = await axios.delete(`${API_URL}/${id}`);
    return res.data;
  } catch (error) {
    console.error("Error al eliminar tarea:", error);
    throw error;
  }
};
