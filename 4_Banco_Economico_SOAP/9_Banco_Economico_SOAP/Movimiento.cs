using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;

namespace _9_Banco_Economico_SOAP
{
    // Clase pública requerida para el contrato de datos SOAP
    public class Movimiento
    {
        public string fecha { get; set; }
        public double monto { get; set; }

        // Constructor vacío obligatorio para la serialización XML de SOAP
        public Movimiento() { }

        public Movimiento(string fecha, double monto)
        {
            this.fecha = fecha;
            this.monto = monto;
        }
    }
}