import { useState, useEffect } from 'react';
import CustomConfirm from '../components/Modals/CustomConfirm.jsx';
import api from '../services/api';

function Veiculos() {
  const [veiculos, setVeiculos] = useState([]);
  const [deleteId, setDeleteId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [editingForm, setEditingForm] = useState(false);
  const [formData, setFormData] = useState({
    modelo: '',
    ano: '',
    data_aquisicao: '',
    kms_rodados: '',
    renavam: '',
    placa: ''
  });

  useEffect(() => {
    fetchVeiculos();
  }, []);

  const fetchVeiculos = async () => {
    try {
      const response = await api.get('/veiculos');
      setVeiculos(response.data.data);
      setLoading(false);
    } catch (error) {
      console.error('Erro ao buscar veículos:', error);
      setLoading(false);
    }
  };

  const hideForm = () => {
      if (showForm) {
        resetForm();
      } else {
        setShowForm(true);
        setEditingForm(true);
      }
  }

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await api.put(`/veiculos/${editingId}`, formData);
      } else {
        await api.post('/veiculos', formData);
      }
      fetchVeiculos();
      resetForm();
    } catch (error) {
      console.error('Erro ao salvar veículo:', error);
      alert('Erro ao salvar veículo');
    }
  };

  const handleEdit = (veiculo) => {
    setFormData({
      modelo: veiculo.modelo,
      ano: veiculo.ano,
      data_aquisicao: veiculo.data_aquisicao,
      kms_rodados: veiculo.kms_rodados,
      renavam: veiculo.renavam,
      placa: veiculo.placa
    });
    setEditingId(veiculo.id);
    setShowForm(true);
  };

  const handleDelete = async () => {
      if (!deleteId) console.log("error");

      try {
          await api.delete(`/veiculos/${deleteId}`);
          setVeiculos(veiculos.filter(v => v.id !== deleteId));
          setDeleteId(null);
      } catch (error) {
          console.error('Erro ao excluir veiculo:', error);
          alert('Erro ao excluir veiculo');
          setDeleteId(null);
      }
  };

  const resetForm = () => {
    setFormData({ modelo: '', ano: '', data_aquisicao: '', kms_rodados: '', renavam: '', placa: '' });
    setEditingId(null);
    setShowForm(false);
    setEditingForm(false);
  };

  if (loading) return <div className="loading">Carregando...</div>;

  return (
    <div className="page">
      <div className="page-header">
        <h1>🚗 Veículos</h1>
        <button onClick={() => hideForm()} className="btn-primary">
          {showForm ? 'Cancelar' : '+ Novo Veículo'}
        </button>
      </div>

      {showForm && (
        <form onSubmit={handleSubmit} className="form-card">
          <h2>{editingId ? 'Editar Veículo' : 'Novo Veículo'}</h2>
          <div className="form-row">
            <div className="form-group">
              <label>Modelo:</label>
              <input
                type="text"
                value={formData.modelo}
                onChange={(e) => setFormData({ ...formData, modelo: e.target.value })}
                required
              />
            </div>
            <div className="form-group">
              <label>Ano:</label>
              <input
                type="number"
                value={formData.ano}
                onChange={(e) => setFormData({ ...formData, ano: e.target.value })}
                required
              />
            </div>
          </div>
          <div className="form-row">
            <div className="form-group">
              <label>Data Aquisição:</label>
              <input
                type="date"
                value={formData.data_aquisicao}
                onChange={(e) => setFormData({ ...formData, data_aquisicao: e.target.value })}
                required
              />
            </div>
            <div className="form-group">
              <label>KMs Rodados:</label>
              <input
                type="number"
                value={formData.kms_rodados}
                onChange={(e) => setFormData({ ...formData, kms_rodados: e.target.value })}
                required
              />
            </div>
          </div>
          <div className="form-row">
            <div className="form-group">
              <label>Renavam:</label>
              <input
                type="text"
                value={formData.renavam}
                onChange={(e) => setFormData({ ...formData, renavam: e.target.value })}
                required
              />
            </div>
            <div className="form-group">
              <label>Placa:</label>
              <input
                type="text"
                value={formData.placa}
                onChange={(e) => setFormData({ ...formData, placa: e.target.value })}
                required
              />
            </div>
          </div>
          <div className="form-actions">
            <button type="submit" className="btn-primary">Salvar</button>
            <button type="button" onClick={resetForm} className="btn-secondary">Cancelar</button>
          </div>
        </form>
      )}

        {!editingForm && (
            <div className="table-container">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Placa</th>
                        <th>Renavam</th>
                        <th>KMs Rodados</th>
                        <th>Data Aquisição</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    {veiculos.filter((veiculo) => veiculo.deletedAt == null)
                        .map((veiculo) => (
                            <tr key={veiculo.id}>
                                <td>{veiculo.id}</td>
                                <td>{veiculo.modelo}</td>
                                <td>{veiculo.ano}</td>
                                <td>{veiculo.placa}</td>
                                <td>{veiculo.renavam}</td>
                                <td>{veiculo.kms_rodados.toLocaleString('pt-BR')}</td>
                                <td>{new Date(veiculo.data_aquisicao).toLocaleDateString('pt-BR')}</td>
                                <td className="actions">
                                    <button onClick={() => handleEdit(veiculo)} className="btn-edit">Editar</button>
                                    <button onClick={() => setDeleteId(veiculo.id)} className="btn-delete">Excluir</button>
                                </td>
                            </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        )}

        {deleteId && (
            <CustomConfirm
                message="Tem certeza que deseja excluir este veiculo?"
                onConfirm={handleDelete}
                onCancel={() => setDeleteId(null)}
            />
        )}
    </div>
  );
}

export default Veiculos;
