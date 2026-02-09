import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Navbar from './components/Navbar';
import Motoristas from './pages/Motoristas';
import Veiculos from './pages/Veiculos';
import Viagens from './pages/Viagens';
import './App.css';

function App() {
  return (
    <Router>
      <div className="app">
        <Navbar />
        <div className="container">
          <Routes>
            <Route path="/" element={<Navigate to="/motoristas" replace />} />
            <Route path="/motoristas" element={<Motoristas />} />
            <Route path="/veiculos" element={<Veiculos />} />
            <Route path="/viagens" element={<Viagens />} />
          </Routes>
        </div>
      </div>
    </Router>
  );
}

export default App;
