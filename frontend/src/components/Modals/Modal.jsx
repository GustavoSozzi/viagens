import style from './Modal.module.css';

const Modal = ({ isOpen, onClose, errors }) => {
    if(!isOpen) return null;

    return (
        <div className={style.overlayStyle}>
            <div className={style.modalStyle}>
                <h2>Erro na requisição</h2>

                {Array.isArray(errors) ? (
                    <ul className={style.errors}>
                        {errors.map((err, index) => (
                            <li className={style.errors} key={index}>{err}</li>
                        ))}
                    </ul>
                ) : (
                    <p>{errors}</p>
                )}

                <button onClick={onClose}>Fechar</button>
            </div>
        </div>
    )
};

export default Modal;

