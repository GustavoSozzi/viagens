import { useState, useEffect } from 'react';
import Modal from '../components/Modals/Modal.jsx';
import CustomConfirm from '../components/Modals/CustomConfirm.jsx';
import api from '../services/api';

function Motoristas() {
  let Error = null;
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [deleteId, setDeleteId] = useState(null);
  const [motoristas, setMotoristas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [loadingIds, setLoadingIds] = useState(null);
  const [showForm, setShowForm] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [errorMessage, setMessage] = useState('');
  const [editingForm, setEditingForm] = useState(false);
  const [errorForm, setErrorForm] = useState(false);
  const [formData, setFormData] = useState({
    nome: '',
    data_nascimento: '',
    numero_cnh: ''
  });

  useEffect(() => {
    fetchMotoristas();
  }, []);

  const hideForm = () => {
      if (showForm) {
        resetForm();
      } else {
        setShowForm(true);
        setEditingForm(true);
      }
  };

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
        if (error.response && error.response.data && error.response.data.errors) {
            const errors = error.response.data.errors;
            const ErrorField = Object.keys(errors)[0];
            const ErrorMessage = errors[ErrorField][0];
            setErrorForm(true)
            setMessage(ErrorMessage);
            setIsModalOpen(true);

            console.error('Erro de validação:', ErrorMessage);

        } else {
            console.error('Erro inesperado:', error);
            alert('Erro inesperado ao tentar salvar o motorista. Tente novamente.');
        }
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

  const handleDelete = async () => {
    if (!deleteId) return;

    const idExclui = deleteId;
    setDeleteId(idExclui);
    setLoadingIds(idExclui);
    setDeleteId(null);
    try {
      await api.delete(`/motoristas/${deleteId}`);
      await new Promise(resolve => setTimeout(resolve, 5000));
      setMotoristas(motoristas.filter(m => m.id !== deleteId));
    } catch (error) {
      console.error('Erro ao excluir motorista:', error);
      alert('Erro ao excluir motorista');
      setDeleteId(null);
    }
  };

  const resetForm = () => {
    setFormData({ nome: '', data_nascimento: '', numero_cnh: '' });
    setEditingId(null);
    setErrorForm(false);
    setShowForm(false);
    setEditingForm(false);
  };

  if (loading) return <div className="loading">Carregando...</div>;

  return (
    <div className="page">
      <div className="page-header">
        <h1>👤 Motoristas</h1>
        <button onClick={() => hideForm()} className="btn-primary">
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
              maxLength="11"
              required
            />
          </div>
          <div className="form-actions">
            <button type="submit" className="btn-primary">Salvar</button>
            <button type="button" onClick={resetForm} className="btn-secondary">Cancelar</button>
          </div>
            {errorForm && (
                <Modal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                errors={errorMessage}
                />
            )}
        </form>

      )}

        {!editingForm && (
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
                    {motoristas.filter((motorista) => motorista.deletedAt == null)
                        .map((motorista) => (
                        <tr key={motorista.id}>
                            <td>{motorista.id}</td>
                            <td>{motorista.nome}</td>
                            <td>{new Date(motorista.data_nascimento).toLocaleDateString('pt-BR')}</td>
                            <td>{motorista.numero_cnh}</td>
                            <td className="actions">
                                <button onClick={() => handleEdit(motorista)} className="btn-edit">Editar</button>
                                <button key={motorista.id} onClick={() => setDeleteId(motorista.id)} disabled={loadingIds === motorista.id} className="btn-delete">{loadingIds === motorista.id ? 'Excluindo...' : 'Excluir'}</button>
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        )}

        {deleteId && (
            <CustomConfirm
                message="Tem certeza que deseja excluir este motorista?"
                onConfirm={handleDelete}
                onCancel={() => setDeleteId(null)}
            />
        )}

    </div>
  )
}

export default Motoristas;
