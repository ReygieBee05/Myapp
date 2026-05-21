<template>
  <div class="container">
    <div class="login-card">
      <h1>Login</h1>
      <p class="subtitle">Welcome back 👋</p>

      <form>
        <input
          type="text"
          placeholder="Username"
          class="input"
        />

        <input
          type="password"
          placeholder="Password"
          class="input"
        />

        <button type="submit" class="login-btn">
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

.container {
  height: 100vh;
  background: linear-gradient(135deg, #141e30, #243b55);
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-card {
  width: 350px;
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  text-align: center;
}

.login-card h1 {
  color: #243b55;
  margin-bottom: 10px;
}

.subtitle {
  color: gray;
  margin-bottom: 30px;
}

.input {
  width: 100%;
  padding: 14px;
  margin-bottom: 15px;
  border: 1px solid #ddd;
  border-radius: 10px;
  outline: none;
  transition: 0.3s;
}

.input:focus {
  border-color: #243b55;
}

.login-btn {
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 10px;
  background: #243b55;
  color: white;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s;
}

.login-btn:hover {
  background: #141e30;
}
</style>