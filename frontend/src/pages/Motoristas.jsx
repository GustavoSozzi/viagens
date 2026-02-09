import { useState, useEffect } from 'react';
import api from '../services/api';

function Motoristas() {
  const [motoristas, setMotoristas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState({
    nome: '',
    data_nascimento: '',
    numero_cnh: ''
  });

  useEffect(() => {
    fetchMotoristas();
  }, []);

  const fetchMotoristas = async () => {
    try {
      const response = await api.get('/motoristas');
      setMotoristas(response.data.data);
      setLoading(false);
    } catch (error) {
      console.error('Erro ao buscar motoristas:', error);
      setLoading(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await api.put(`/motoristas/${editingId}`, formData);
      } else {
        await api.post('/motoristas', formData);
      }
      fetchMotoristas();
      resetForm();
    } catch (error) {
      console.error('Erro ao salvar motorista:', error);
      alert('Erro ao salvar motorista');
    }
  };

  const handleEdit = (motorista) => {
    setFormData({
      nome: motorista.nome,
      data_nascimento: motorista.data_nascimento,
      numero_cnh: motorista.numero_cnh
    });
    setEditingId(motorista.id);
    setShowForm(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Tem certeza que deseja excluir este motorista?')) {
      try {
        await api.delete(`/motoristas/${id}`);
        fetchMotoristas();
      } catch (error) {
        console.error('Erro ao excluir motorista:', error);
        alert('Erro ao excluir motorista');
      }
    }
  };

  const resetForm = () => {
    setFormData({ nome: '', data_nascimento: '', numero_cnh: '' });
    setEditingId(null);
    setShowForm(false);
  };

  if (loading) return <div className="loading">Carregando...</div>;

  return (
    <div className="page">
      <div className="page-header">
        <h1>👤 Motoristas</h1>
        <button onClick={() => setShowForm(!showForm)} className="btn-primary">
          {showForm ? 'Cancelar' : '+ Novo Motorista'}
        </button>
      </div>

      {showForm && (
        <form onSubmit={handleSubmit} className="form-card">
          <h2>{editingId ? 'Editar Motorista' : 'Novo Motorista'}</h2>
          <div className="form-group">
            <label>Nome:</label>
            <input
              type="text"
              value={formData.nome}
              onChange={(e) => setFormData({ ...formData, nome: e.target.value })}
              required
            />
          </div>
          <div className="form-group">
            <label>Data de Nascimento:</label>
            <input
              type="date"
              value={formData.data_nascimento}
              onChange={(e) => setFormData({ ...formData, data_nascimento: e.target.value })}
              required
            />
          </div>
          <div className="form-group">
            <label>Número CNH:</label>
            <input
              type="text"
              value={formData.numero_cnh}
              onChange={(e) => setFormData({ ...formData, numero_cnh: e.target.value })}
              required
            />
          </div>
          <div className="form-actions">
            <button type="submit" className="btn-primary">Salvar</button>
            <button type="button" onClick={resetForm} className="btn-secondary">Cancelar</button>
          </div>
        </form>
      )}

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Data Nascimento</th>
              <th>CNH</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            {motoristas.map((motorista) => (
              <tr key={motorista.id}>
                <td>{motorista.id}</td>
                <td>{motorista.nome}</td>
                <td>{new Date(motorista.data_nascimento).toLocaleDateString('pt-BR')}</td>
                <td>{motorista.numero_cnh}</td>
                <td className="actions">
                  <button onClick={() => handleEdit(motorista)} className="btn-edit">Editar</button>
                  <button onClick={() => handleDelete(motorista.id)} className="btn-delete">Excluir</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default Motoristas;
