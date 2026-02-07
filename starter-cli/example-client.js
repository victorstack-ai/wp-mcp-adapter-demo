const baseUrl = process.env.MCP_SERVER_URL;

if (!baseUrl) {
  console.error('Missing MCP_SERVER_URL env var.');
  process.exit(1);
}

async function run() {
  const capabilities = await fetch(`${baseUrl}/capabilities`).then((res) => res.json());
  console.log('Capabilities:', JSON.stringify(capabilities, null, 2));

  const response = await fetch(`${baseUrl}/tool`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name: 'site_summary',
      args: { include_url: true }
    })
  }).then((res) => res.json());

  console.log('Tool response:', JSON.stringify(response, null, 2));
}

run().catch((error) => {
  console.error(error);
  process.exit(1);
});
