/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.sis258._banco_economico_soap;
import java.io.Serializable;

/**
 *
 * @author Ruta Binar
 */
public class Movimiento implements Serializable{
    private String fecha;
    private double monto;

    // Constructor vacío obligatorio para SOAP
    public Movimiento() {
    }

    public Movimiento(String fecha, double monto) {
        this.fecha = fecha;
        this.monto = monto;
    }

    // Getters y Setters necesarios para la serialización
    public String getFecha() { return fecha; }
    public void setFecha(String fecha) { this.fecha = fecha; }

    public double getMonto() { return monto; }
    public void setMonto(double monto) { this.monto = monto; }
}
