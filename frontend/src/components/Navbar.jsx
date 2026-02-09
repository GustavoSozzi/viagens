import { Link, useLocation } from 'react-router-dom';

function Navbar() {
  const location = useLocation();

  return (
    <nav className="navbar">
      <div className="navbar-brand">
        <h2>🚗 Sistema de Viagens</h2>
      </div>
      <div className="navbar-links">
        <Link to="/motoristas" className={location.pathname === '/motoristas' ? 'active' : ''}>
          Motoristas
        </Link>
        <Link to="/veiculos" className={location.pathname === '/veiculos' ? 'active' : ''}>
          Veículos
        </Link>
        <Link to="/viagens" className={location.pathname === '/viagens' ? 'active' : ''}>
          Viagens
        </Link>
      </div>
    </nav>
  );
}

export default Navbar;
