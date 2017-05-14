<h1>$title</h1>
<form action="$baseUrl/personas/guardar" method="post">
  <div style="width: 20%;">
    <label>Id:</label>
  </div>
  <div style="width: 40%;">
    <input
      type="text"
      disabled
      value="$persona->persona_id"
    />
    <input
      type="hidden"
      id="personaId"
      name="personaId"
      value="$persona->persona_id"
    />
  </div>
  <br />
  <div style="width: 20%;">
    <label>Nombre:</label>
  </div>
  <div style="width: 40%;">
    <input
      type="text"
      id="nombre"
      name="nombre"
      value="$persona->nombre"
    />
  </div>
  <br />
  <div style="width: 20%;">
    <label>Edad:</label>
  </div>
  <div style="width: 40%;">
    <input
      type="number"
      id="edad"
      name="edad"
      value="$persona->edad"
    />
  </div>
  <br />
  <input
    type="submit"
    value="Guardar"
  />
</form>
