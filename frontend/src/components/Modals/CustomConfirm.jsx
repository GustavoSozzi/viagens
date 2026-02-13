import React, { useState } from 'react';
import style from './Modal.module.css';

const ModalConfirm = ({ message, onConfirm, onCancel }) => {
    return (
        <div className={style.overlayStyle}>
            <div className={style.style.modalStyle}>
                <p>{message}</p>
                <button onClick={onConfirm}>Sim</button>
                <button onClick={onCancel}>Não</button>
            </div>
        </div>
    );
};

export default ModalConfirm;
