using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Net.Http;
using Newtonsoft.Json;

namespace Comercio2_App
{
    public partial class Form1 : Form
    {
        // ==========================================
        // 1. FUNCIÓN PARA OBTENER EL TOKEN (CORREGIDA)
        // ==========================================
        private async System.Threading.Tasks.Task<string> ObtenerTokenJWT()
        {
            using (HttpClient client = new HttpClient())
            {
                try
                {
                    // Credenciales de tu Insomnia
                    var credenciales = new { email = "comercio1@gmail.com", password = "comercio123" };
                    string jsonLogin = JsonConvert.SerializeObject(credenciales);
                    var content = new StringContent(jsonLogin, Encoding.UTF8, "application/json");

                    // Petición POST al login
                    HttpResponseMessage response = await client.PostAsync("http://127.0.0.1:8000/api/login", content);

                    if (response.IsSuccessStatusCode)
                    {
                        string respuestaJson = await response.Content.ReadAsStringAsync();

                        // Deserializamos la respuesta de Laravel
                        dynamic resultado = JsonConvert.DeserializeObject(respuestaJson);

                        // CORRECCIÓN AQUÍ: Cambiado de access_token a .token para igualar tu Insomnia
                        return resultado.token;
                    }
                }
                catch (Exception ex)
                {
                    System.Diagnostics.Debug.WriteLine("Error obteniendo Token: " + ex.Message);
                }
            }
            return null;
        }
        public Form1()
        {
            InitializeComponent();
        }

        private void textBox1_TextChanged(object sender, EventArgs e)
        {

        }

        private void btnConsutarSaldo_Click(object sender, EventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {

        }

        private void textOrigen_TextChanged(object sender, EventArgs e)
        {
        }

        // ==========================================
        // 2. BOTÓN DE TRANSACCIÓN REST (CORREGIDO)
        // ==========================================
        private async void btnTransaccion_Click(object sender, EventArgs e)
        {
            // 1. VALIDACIÓN
            if (string.IsNullOrEmpty(txtOrigen.Text) || string.IsNullOrEmpty(txtDestino.Text) || string.IsNullOrEmpty(txtMonto.Text))
            {
                MessageBox.Show("Por favor, llene todos los campos.", "Campos Vacíos", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            // 2. OBTENER EL TOKEN
            string token = await ObtenerTokenJWT();

            if (string.IsNullOrEmpty(token))
            {
                MessageBox.Show("No se pudo autenticar con el intermediador (JWT inválido o credenciales erróneas).", "Error de Autenticación", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            // 3. PREPARAR DATOS DEL PAGO (CORREGIDO CON GUIONES BAJOS)
            // Cambiado para emparejar la estructura exacta que lee tu backend de Laravel
            var datosDelPago = new
            {
                fecha = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"),
                cuenta_origen = txtOrigen.Text,   // CORRECCIÓN: antes era cuentaOrigen
                cuenta_destino = txtDestino.Text, // CORRECCIÓN: antes era cuentaDestino
                monto = Convert.ToDouble(txtMonto.Text)
            };

            string jsonFinal = JsonConvert.SerializeObject(datosDelPago);
            var contenidoParaEnviar = new StringContent(jsonFinal, Encoding.UTF8, "application/json");

            // 4. ENVIAR CON AUTORIZACIÓN JWT
            using (HttpClient clienteRest = new HttpClient())
            {
                try
                {
                    // Adjuntamos el Token Bearer a la cabecera
                    clienteRest.DefaultRequestHeaders.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("Bearer", token);

                    string urlIntermediador = "http://127.0.0.1:8000/api/transaccion";

                    // Enviamos la petición POST
                    HttpResponseMessage respuesta = await clienteRest.PostAsync(urlIntermediador, contenidoParaEnviar);

                    if (respuesta.IsSuccessStatusCode)
                    {
                        string resultadoServidor = await respuesta.Content.ReadAsStringAsync();
                        MessageBox.Show("¡Transacción Exitosa!\nRespuesta: " + resultadoServidor, "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information);

                        txtOrigen.Clear();
                        txtDestino.Clear();
                        txtMonto.Clear();
                    }
                    else
                    {
                        // Si da error, leemos el porqué para no adivinar
                        string errorServidor = await respuesta.Content.ReadAsStringAsync();
                        MessageBox.Show("Transacción rechazada por el Intermediador.\nCódigo HTTP: " + respuesta.StatusCode + "\nDetalle: " + errorServidor, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Error de conexión de red: " + ex.Message, "Error de Red", MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            }
        }
    }
}