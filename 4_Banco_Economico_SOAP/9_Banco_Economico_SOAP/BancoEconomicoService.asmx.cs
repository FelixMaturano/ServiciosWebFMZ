using _9_Banco_Economico_SOAP;
using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;
using System.Web.Services;

namespace _9_Banco_Economico_SOAP
{
    [WebService(Namespace = "http://bancoeconomico.org/soap/")]
    [WebServiceBinding(ConformsTo = WsiProfiles.BasicProfile1_1)]
    [System.ComponentModel.ToolboxItem(false)]
    public class BancoEconomicoService : System.Web.Services.WebService
    {
        // Cadena de conexión directa a la base de datos compartida de Laravel
        private string conexionString = "Server=127.0.0.1;Port=3307;Database=bd_banco_economico_api;Uid=root;Pwd=;";

        // ====================================================================
        // OPERACIÓN 1: Consultar Saldo Actual
        // ====================================================================
        [WebMethod(Description = "Devuelve el saldo actual de una cuenta específica.")]
        public double consultarSaldo(string cuenta)
        {
            double saldoActual = 0.0;

            using (MySqlConnection conexion = new MySqlConnection(conexionString))
            {
                string query = "SELECT saldo FROM cuentas WHERE cuenta = @cuenta LIMIT 1";
                MySqlCommand comando = new MySqlCommand(query, conexion);
                comando.Parameters.AddWithValue("@cuenta", cuenta);

                try
                {
                    conexion.Open();
                    object resultado = comando.ExecuteScalar();
                    if (resultado != null)
                    {
                        saldoActual = Convert.ToDouble(resultado);
                    }
                    else
                    {
                        // Si la cuenta no existe, lanzamos un error descriptivo
                        throw new Exception("La cuenta proporcionada no existe en el Banco Económico.");
                    }
                }
                catch (Exception ex)
                {
                    // 🌟 CORRECCIÓN: Usamos Exception para que sea compatible con el protocolo SOAP
                    throw new Exception("Error en el Servidor SOAP: " + ex.Message);
                }
            }

            return saldoActual;
        }

        // ====================================================================
        // OPERACIÓN 2: Historial de Movimientos
        // ====================================================================
        [WebMethod(Description = "Devuelve la lista histórica de movimientos de una cuenta.")]
        public List<Movimiento> historial(string cuenta)
        {
            List<Movimiento> listaMovimientos = new List<Movimiento>();

            using (MySqlConnection conexion = new MySqlConnection(conexionString))
            {
                // Buscamos en la tabla movimientos que creamos mediante el observer de Laravel
                string query = "SELECT fecha, monto FROM movimientos WHERE cuenta_id = @cuenta ORDER BY fecha DESC";
                MySqlCommand comando = new MySqlCommand(query, conexion);
                comando.Parameters.AddWithValue("@cuenta", cuenta);

                try
                {
                    conexion.Open();
                    using (MySqlDataReader lector = comando.ExecuteReader())
                    {
                        while (lector.Read())
                        {
                            listaMovimientos.Add(new Movimiento(
                                Convert.ToDateTime(lector["fecha"]).ToString("yyyy-MM-dd HH:mm:ss"),
                                Convert.ToDouble(lector["monto"])
                            ));
                        }
                    }
                }
                catch (Exception ex)
                {
                    // 🌟 CORRECCIÓN: Usamos Exception para evitar el error de constructor
                    throw new Exception("Error al extraer el historial SOAP: " + ex.Message);
                }
            }

            return listaMovimientos;
        }
    }
}