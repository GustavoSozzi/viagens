import { useState, useEffect } from 'react';
import api from '../services/api';
import CustomConfirm from "../components/Modals/CustomConfirm.jsx";

function Viagens() {
  const [viagens, setViagens] = useState([]);
  const [veiculos, setVeiculos] = useState([]);
  const [loadingIds, setLoadingIds] = useState(null);
  const [deleteId, setDeleteId] = useState(null);
  const [motoristas, setMotoristas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [carregando, setCarregando] = useState(true);
  const [editingForm, setEditingForm] = useState(false)
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState({
    veiculo_id: '',
    km_inicial: '',
    km_final: '',
    data_hora_inicial: '',
    data_hora_final: '',
    motorista_ids: []
  });

  useEffect(() => {
    fetchData();
  }, [carregando]);

  const fetchData = async () => {
    try {
      const [viagensRes, veiculosRes, motoristasRes] = await Promise.all([
        api.get('/viagens'),
        api.get('/veiculos'),
        api.get('/motoristas')
      ]);
      setViagens(viagensRes.data.data);
      setVeiculos(veiculosRes.data.data);
      setMotoristas(motoristasRes.data.data);
      setLoading(false);
      setCarregando(false);
    } catch (error) {
      console.error('Erro ao buscar dados:', error);
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

    // Validar se pelo menos um motorista foi selecionado
    if (formData.motorista_ids.length === 0) {
      alert('Selecione pelo menos um motorista para a viagem');
      return;
    }

    try {
      const payload = {
        veiculo_id: formData.veiculo_id,
        km_inicial: parseInt(formData.km_inicial),
        km_final: formData.km_final ? parseInt(formData.km_final) : null,
        data_hora_inicial: formData.data_hora_inicial,
        data_hora_final: formData.data_hora_final || null,
        motoristas: formData.motorista_ids
      };

      if (editingId) {
        await api.put(`/viagens/${editingId}`, payload);
      } else {
        await api.post('/viagens', payload);
      }
      fetchData();
      resetForm();
    } catch (error) {
      console.error('Erro ao salvar viagem:', error);
      const errorMsg = error.response?.data?.errors
        ? Object.values(error.response.data.errors).flat().join('\n')
        : error.response?.data?.message || 'Erro ao salvar viagem';
      alert(errorMsg);
    }
  };

  const handleEdit = (viagem) => {
    setFormData({
      veiculo_id: viagem.veiculo_id,
      km_inicial: viagem.km_inicial,
      km_final: viagem.km_final || '',
      data_hora_inicial: viagem.data_hora_inicial.slice(0, 16),
      data_hora_final: viagem.data_hora_final ? viagem.data_hora_final.slice(0, 16) : '',
      motorista_ids: viagem.motoristas.map(m => m.id)
    });
    setEditingId(viagem.id);
    setShowForm(true);
    setEditingForm(true);
  };

  const handleDelete = async (id) => {
      setDeleteId(id);
      if (!deleteId) console.log("error");

      const idExclui = deleteId;
      setDeleteId(idExclui);
      setLoadingIds(idExclui);
      setDeleteId(null);

      console.log('viagem excluida: ' + deleteId);
      try {
        await api.delete(`/viagens/${deleteId}`);
        await new Promise(resolve => setTimeout(resolve, 5000));
        setViagens(viagens.filter(v => v.id !== deleteId));
      } catch (error) {
        console.error('Erro ao excluir viagem:', error);
        alert('Erro ao excluir viagem');
      } finally {
          setLoadingIds(null);
          setCarregando(false);
      }
    }

  const handleMotoristaToggle = (motoristaId) => {
    setFormData(prev => ({
      ...prev,
      motorista_ids: prev.motorista_ids.includes(motoristaId)
        ? prev.motorista_ids.filter(id => id !== motoristaId)
        : [...prev.motorista_ids, motoristaId]
    }));
  };

  const resetForm = () => {
    setFormData({
      veiculo_id: '',
      km_inicial: '',
      km_final: '',
      data_hora_inicial: '',
      data_hora_final: '',
      motorista_ids: [],
    });
    setEditingId(null);
    setShowForm(false);
    setEditingForm(false);
  };

  const getVeiculoNome = (id) => {
    const veiculo = veiculos.find(v => v.id === id);
    return veiculo ? `${veiculo.modelo} - ${veiculo.placa}` : 'N/A';
  };

  const calcularDuracao = (inicio, fim) => {
    if (!fim) return 'Em andamento';
    const diff = new Date(fim) - new Date(inicio);
    const horas = Math.floor(diff / (1000 * 60 * 60));
    const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    return `${horas}h ${minutos}min`;
  };

  const calcularDistancia = (kmInicial, kmFinal) => {
    if (!kmFinal) return '-';
    return `${(kmFinal - kmInicial).toLocaleString('pt-BR')} km`;
  };

  // Estatísticas
  const totalViagens = viagens.length;
  const viagensEmAndamento = viagens.filter(v => !v.km_final).length;
  const viagensFinalizadas = viagens.filter(v => v.km_final).length;
  const totalKmPercorrido = viagens
    .filter(v => v.km_final)
    .reduce((acc, v) => acc + (v.km_final - v.km_inicial), 0);

  if (loading) return <div className="loading">Carregando...</div>;

  return (
    <div className="page">
      <div className="page-header">
        <h1>📍 Viagens</h1>
        <button onClick={() => hideForm()} className="btn-primary">
          {showForm ? 'Cancelar' : '+ Nova Viagem'}
        </button>
      </div>

      {/* Estatísticas */}
      <div className="stats-grid">
        <div className="stat-card">
          <h3>Total de Viagens</h3>
          <div className="value">{totalViagens}</div>
        </div>
        <div className="stat-card">
          <h3>Em Andamento</h3>
          <div className="value" style={{color: '#f6ad55'}}>{viagensEmAndamento}</div>
        </div>
        <div className="stat-card">
          <h3>Finalizadas</h3>
          <div className="value" style={{color: '#48bb78'}}>{viagensFinalizadas}</div>
        </div>
        <div className="stat-card">
          <h3>KM Total Percorrido</h3>
          <div className="value">{totalKmPercorrido.toLocaleString('pt-BR')}</div>
        </div>
      </div>


      {showForm && (
        <form onSubmit={handleSubmit} className="form-card">
          <h2>{editingId ? 'Editar Viagem' : 'Nova Viagem'}</h2>

          <div className="form-group">
            <label>Veículo:</label>
            <select
              value={formData.veiculo_id}
              onChange={(e) => setFormData({ ...formData, veiculo_id: e.target.value })}
              required
            >
              <option value="">Selecione um veículo</option>
              {veiculos.map(veiculo => (
                <option key={veiculo.id} value={veiculo.id}>
                  {veiculo.modelo} - {veiculo.placa}
                </option>
              ))}
            </select>
          </div>

          <div className="form-group">
            <label>Motoristas: {formData.motorista_ids.length > 0 && <span style={{color: '#667eea'}}>({formData.motorista_ids.length} selecionado{formData.motorista_ids.length > 1 ? 's' : ''})</span>}</label>
            <div className="checkbox-group">
              {motoristas.map(motorista => (
                <label key={motorista.id} className="checkbox-label">
                  <input
                    type="checkbox"
                    checked={formData.motorista_ids.includes(motorista.id)}
                    onChange={() => handleMotoristaToggle(motorista.id)}
                  />
                  {motorista.nome}
                </label>
              ))}
            </div>
          </div>

          <div className="form-row">
            <div className="form-group">
              <label>KM Inicial:</label>
              <input
                type="number"
                value={formData.km_inicial}
                onChange={(e) => setFormData({ ...formData, km_inicial: e.target.value })}
                required
              />
            </div>
            <div className="form-group">
              <label>KM Final (opcional):</label>
              <input
                type="number"
                value={formData.km_final}
                onChange={(e) => setFormData({ ...formData, km_final: e.target.value })}
                placeholder="Deixe vazio se em andamento"
              />
            </div>
          </div>

          <div className="form-row">
            <div className="form-group">
              <label>Data/Hora Inicial:</label>
              <input
                type="datetime-local"
                value={formData.data_hora_inicial}
                onChange={(e) => setFormData({ ...formData, data_hora_inicial: e.target.value })}
                required
              />
            </div>
            <div className="form-group">
              <label>Data/Hora Final (opcional):</label>
              <input
                type="datetime-local"
                value={formData.data_hora_final}
                onChange={(e) => setFormData({ ...formData, data_hora_final: e.target.value })}
                placeholder="Deixe vazio se em andamento"
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
                        <th>Veículo</th>
                        <th>Motoristas</th>
                        <th>Distância</th>
                        <th>Duração</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    {viagens.map((viagem) => (
                        <tr key={viagem.id}>
                            <td><strong>#{viagem.id}</strong></td>
                            <td>{getVeiculoNome(viagem.veiculo_id)}</td>
                            <td>
                                {viagem.motoristas.map(m => (
                                    <span key={m.id} className="badge badge-primary">{m.nome}</span>
                                ))}
                            </td>
                            <td><strong>{calcularDistancia(viagem.km_inicial, viagem.km_final)}</strong></td>
                            <td>{calcularDuracao(viagem.data_hora_inicial, viagem.data_hora_final)}</td>
                            <td>{new Date(viagem.data_hora_inicial).toLocaleString('pt-BR')}</td>
                            <td>{viagem.data_hora_final ? new Date(viagem.data_hora_final).toLocaleString('pt-BR') : '-'}</td>
                            <td>
                            <span className={`status ${viagem.km_final ? 'finalizada' : 'andamento'}`}>
                            {viagem.km_final ? '✓ Finalizada' : '⏱ Em Andamento'}
                            </span>
                            </td>
                            <td className="actions">
                                <button onClick={() => handleEdit(viagem)} className="btn-edit">Editar</button>
                                <button onClick={() => setDeleteId(viagem.id)} disabled={loadingIds === viagem.id} className="btn-delete">{loadingIds === viagem.id ? 'Excluindo...' : 'Excluir'}</button>
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        )}

        {deleteId && (
            <CustomConfirm
                message="Tem certeza que deseja excluir esta viagem?"
                onConfirm={handleDelete}
                onCancel={() => setDeleteId(null)}
            />
        )}
    </div>
  );
}

export default Viagens;
