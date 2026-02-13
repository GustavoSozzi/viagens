import React, { useState } from 'react';
import style from './Modal.module.css';

const CustomConfirm = ({ message, onConfirm, onCancel }) => {
    return (
        <div className={style.overlayStyle}>
            <div className={style.modalStyle}>
                <p>{message}</p>
                <button onClick={onConfirm}>Sim</button>
                <button onClick={onCancel}>Não</button>
            </div>
        </div>
    );
};

export default CustomConfirm;
