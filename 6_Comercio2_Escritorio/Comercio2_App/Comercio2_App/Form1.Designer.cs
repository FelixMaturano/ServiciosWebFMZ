namespace Comercio2_App
{
    partial class Form1
    {
        /// <summary>
        /// Variable del diseñador necesaria.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Limpiar los recursos que se estén usando.
        /// </summary>
        /// <param name="disposing">true si los recursos administrados se deben desechar; false en caso contrario.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Código generado por el Diseñador de Windows Forms

        /// <summary>
        /// Método necesario para admitir el Diseñador. No se puede modificar
        /// el contenido de este método con el editor de código.
        /// </summary>
        private void InitializeComponent()
        {
            this.txtCuentaConsulta = new System.Windows.Forms.TextBox();
            this.btnConsutarSaldo = new System.Windows.Forms.Button();
            this.btnVerHistorial = new System.Windows.Forms.Button();
            this.lblSaldo = new System.Windows.Forms.Label();
            this.dgvHistorial = new System.Windows.Forms.DataGridView();
            this.txtOrigen = new System.Windows.Forms.TextBox();
            this.txtMonto = new System.Windows.Forms.TextBox();
            this.btnTransaccion = new System.Windows.Forms.Button();
            this.txtDestino = new System.Windows.Forms.TextBox();
            ((System.ComponentModel.ISupportInitialize)(this.dgvHistorial)).BeginInit();
            this.SuspendLayout();
            // 
            // txtCuentaConsulta
            // 
            this.txtCuentaConsulta.Location = new System.Drawing.Point(98, 45);
            this.txtCuentaConsulta.Name = "txtCuentaConsulta";
            this.txtCuentaConsulta.Size = new System.Drawing.Size(202, 22);
            this.txtCuentaConsulta.TabIndex = 0;
            this.txtCuentaConsulta.TextChanged += new System.EventHandler(this.textBox1_TextChanged);
            // 
            // btnConsutarSaldo
            // 
            this.btnConsutarSaldo.Location = new System.Drawing.Point(108, 85);
            this.btnConsutarSaldo.Name = "btnConsutarSaldo";
            this.btnConsutarSaldo.Size = new System.Drawing.Size(172, 23);
            this.btnConsutarSaldo.TabIndex = 1;
            this.btnConsutarSaldo.Text = "Consultar Saldo";
            this.btnConsutarSaldo.UseVisualStyleBackColor = true;
            this.btnConsutarSaldo.Click += new System.EventHandler(this.btnConsutarSaldo_Click);
            // 
            // btnVerHistorial
            // 
            this.btnVerHistorial.Location = new System.Drawing.Point(108, 114);
            this.btnVerHistorial.Name = "btnVerHistorial";
            this.btnVerHistorial.Size = new System.Drawing.Size(172, 23);
            this.btnVerHistorial.TabIndex = 2;
            this.btnVerHistorial.Text = "Ver Historial";
            this.btnVerHistorial.UseVisualStyleBackColor = true;
            this.btnVerHistorial.Click += new System.EventHandler(this.button1_Click);
            // 
            // lblSaldo
            // 
            this.lblSaldo.AutoSize = true;
            this.lblSaldo.Location = new System.Drawing.Point(72, 140);
            this.lblSaldo.Name = "lblSaldo";
            this.lblSaldo.Size = new System.Drawing.Size(57, 16);
            this.lblSaldo.TabIndex = 3;
            this.lblSaldo.Text = "lblSaldo";
            // 
            // dgvHistorial
            // 
            this.dgvHistorial.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.dgvHistorial.Location = new System.Drawing.Point(98, 170);
            this.dgvHistorial.Name = "dgvHistorial";
            this.dgvHistorial.RowHeadersWidth = 51;
            this.dgvHistorial.RowTemplate.Height = 24;
            this.dgvHistorial.Size = new System.Drawing.Size(240, 150);
            this.dgvHistorial.TabIndex = 4;
            // 
            // txtOrigen
            // 
            this.txtOrigen.Location = new System.Drawing.Point(12, 340);
            this.txtOrigen.Name = "txtOrigen";
            this.txtOrigen.Size = new System.Drawing.Size(141, 22);
            this.txtOrigen.TabIndex = 5;
            this.txtOrigen.TextChanged += new System.EventHandler(this.textOrigen_TextChanged);
            // 
            // txtMonto
            // 
            this.txtMonto.Location = new System.Drawing.Point(370, 340);
            this.txtMonto.Name = "txtMonto";
            this.txtMonto.Size = new System.Drawing.Size(150, 22);
            this.txtMonto.TabIndex = 7;
            // 
            // btnTransaccion
            // 
            this.btnTransaccion.Location = new System.Drawing.Point(144, 377);
            this.btnTransaccion.Name = "btnTransaccion";
            this.btnTransaccion.Size = new System.Drawing.Size(136, 23);
            this.btnTransaccion.TabIndex = 8;
            this.btnTransaccion.Text = "button1";
            this.btnTransaccion.UseVisualStyleBackColor = true;
            this.btnTransaccion.Click += new System.EventHandler(this.btnTransaccion_Click);
            // 
            // txtDestino
            // 
            this.txtDestino.Location = new System.Drawing.Point(159, 340);
            this.txtDestino.Name = "txtDestino";
            this.txtDestino.Size = new System.Drawing.Size(194, 22);
            this.txtDestino.TabIndex = 9;
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(800, 450);
            this.Controls.Add(this.txtDestino);
            this.Controls.Add(this.btnTransaccion);
            this.Controls.Add(this.txtMonto);
            this.Controls.Add(this.txtOrigen);
            this.Controls.Add(this.dgvHistorial);
            this.Controls.Add(this.lblSaldo);
            this.Controls.Add(this.btnVerHistorial);
            this.Controls.Add(this.btnConsutarSaldo);
            this.Controls.Add(this.txtCuentaConsulta);
            this.Name = "Form1";
            this.Text = "Form1";
            ((System.ComponentModel.ISupportInitialize)(this.dgvHistorial)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.TextBox txtCuentaConsulta;
        private System.Windows.Forms.Button btnConsutarSaldo;
        private System.Windows.Forms.Button btnVerHistorial;
        private System.Windows.Forms.Label lblSaldo;
        private System.Windows.Forms.DataGridView dgvHistorial;
        private System.Windows.Forms.TextBox txtOrigen;
        private System.Windows.Forms.TextBox txtMonto;
        private System.Windows.Forms.Button btnTransaccion;
        private System.Windows.Forms.TextBox txtDestino;
    }
}

