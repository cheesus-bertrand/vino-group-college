import axios from "axios";

const api = axios.create({
  // baseURL: "http://127.0.0.1:8000",
  baseURL: "/api",
  withCredentials: true,
});

export const fetchCsrfToken = () => api.get("/csrf-token");

export default api;
