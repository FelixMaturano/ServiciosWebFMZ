/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/WebServices/WebService.java to edit this template
 */
package com.sis258._banco_economico_soap;

import jakarta.jws.WebService;
import jakarta.jws.WebMethod;
import jakarta.jws.WebParam;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;
/**
 *
 * @author Ruta Binar
 */
@WebService(serviceName = "BancoEconomicoWS")
public class BancoEconomicoWS {

    // Configuración JDBC para MySQL
    private final String URL = "jdbc:mysql://localhost:3307/bd_banco_economico_api";
    private final String USER = "root";
    private final String PASSWORD = ""; // Añade tu contraseña de MySQL si la tienes

    private Connection obtenerConexion() throws Exception {
        // Al usar dependencias Maven modernas, la carga manual de la clase driver 
        // ya no es estrictamente obligatoria, pero se incluye por máxima compatibilidad.
        Class.forName("com.mysql.cj.jdbc.Driver"); 
        return DriverManager.getConnection(URL, USER, PASSWORD);
    }

    /**
     * OPERACIÓN 1: Consultar saldo actual en base de datos
     */
    @WebMethod(operationName = "consultarSaldo")
    public Double consultarSaldo(@WebParam(name = "cuenta") String cuenta) {
        Double saldo = null;
        String query = "SELECT saldo FROM cuentas WHERE cuenta = ?";
        
        try (Connection con = obtenerConexion();
             PreparedStatement ps = con.prepareStatement(query)) {
            
            ps.setString(1, cuenta);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    saldo = rs.getDouble("saldo");
                }
            }
        } catch (Exception e) {
            System.err.println("Error en consultarSaldo SOAP: " + e.getMessage());
        }
        return saldo; // Devuelve null si la cuenta no se localiza
    }

    /**
     * OPERACIÓN 2: Consultar historial de movimientos
     */
    @WebMethod(operationName = "historial")
    public List<Movimiento> historial(@WebParam(name = "cuenta") String cuenta) {
        List<Movimiento> lista = new ArrayList<>();
        String query = "SELECT cuenta FROM cuentas WHERE cuenta = ?";
        
        try (Connection con = obtenerConexion();
             PreparedStatement ps = con.prepareStatement(query)) {
            
            ps.setString(1, cuenta);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    // Cuentas válidas devuelven datos controlados de prueba para el laboratorio
                    lista.add(new Movimiento("2026-05-19 10:00:00", 250.50));
                    lista.add(new Movimiento("2026-05-20 15:30:00", -100.00));
                    lista.add(new Movimiento("2026-05-21 11:00:00", 75.00));
                }
            }
        } catch (Exception e) {
            System.err.println("Error en historial SOAP: " + e.getMessage());
        }
        return lista;
    }
}
