<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ChatGPT UI Clone</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="h-screen bg-white flex overflow-hidden text-gray-900">

  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <div class="px-4 py-6 space-y-3">
      <div class="flex items-center space-x-2">
        <i class="fa-brands fa-openai text-2xl"></i>
        <span class="text-lg font-semibold">ChatGPT</span>
      </div>
      
      <button id="newChatBtn" class="w-full flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded">
        <i class="fas fa-plus mr-2"></i> New chat
      </button>
      
      <button id="searchChatBtn" class="w-full flex items-center px-3 py-2 hover:bg-gray-100 rounded">
        <i class="fas fa-search mr-2"></i> Search chats
      </button>
      
      <button id="libraryBtn" class="w-full flex items-center px-3 py-2 hover:bg-gray-100 rounded">
        <i class="fas fa-book mr-2"></i> Library
      </button>
      
      <button id="soraBtn" class="w-full flex items-center px-3 py-2 hover:bg-gray-100 rounded">
        <i class="fas fa-film mr-2"></i> Sora
      </button>
      
      <button id="gptsBtn" class="w-full flex items-center px-3 py-2 hover:bg-gray-100 rounded">
        <i class="fas fa-layer-group mr-2"></i> GPTs
      </button>
    </div>
    <div class="px-4 py-2 text-sm font-semibold text-gray-600">Chats</div>
    <div class="overflow-y-auto px-4 flex-1">
        <ul id="chatHistory" class="space-y-1 text-sm">
          <!-- New chats will be appended here -->
        </ul>
    </div>
  </aside>

  <!-- Main content -->
  <div class="flex-1 flex flex-col h-full">
    <!-- Top Navbar -->
    <header class="w-full flex items-center justify-between px-6 py-4">
      <div class="text-xl font-semibold">ChatGPT<span style="font-size: small;"> (GPT-3.5 turbo)</span></div>
      
      <img src="./images/KEN_LOGO1.jpg" 
           alt="Watermark" width="150" height="120"
           class="absolute z-0 pointer-events-none"
           style="opacity:0.2;top: 15%; left: 50%; transform: translate(5%, -80%);" />
      <div class="flex items-center gap-4">
        <button class="bg-purple-100 text-purple-700 text-xs font-semibold px-3 py-1 rounded-full">Get Plus - (GPT-4.0 turbo)</button>
      </div>
    </header>

    <!-- Centered content -->
    <main class="flex-1 relative flex flex-col items-center justify-start text-center px-4 overflow-auto">

      <!-- ChatGPT API UI -->
        <div class="bg-white border border-gray-200 shadow p-6 rounded-md w-full max-w-3xl z-10 text-left space-y-6" style="margin-top: 5%;">
        <div class="text-center"><h1 class="text-2xl font-medium mb-6 z-10">What are you working on?</h1></div>
          <!-- 🔽 Search Input (Ask Anything) -->
          <div class="relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ask a Question - <span style="color: #888;">(Sales & Stitching/Production only)</span></label>
            <div class="flex items-center bg-white border border-gray-300 shadow-sm rounded-full px-4 py-4 hover:shadow transition">
              <button class="text-gray-500 hover:text-black transition mr-3">
                <i class="fas fa-plus"></i>
              </button>
              <input
                id="userQuestion"
                type="text"
                placeholder="Ask anything..."
                class="flex-1 bg-transparent focus:outline-none text-sm placeholder-gray-400"
              />
              <button id="askGPT" class="text-blue-600 hover:text-blue-800 transition ml-3">
                <i class="fas fa-paper-plane text-lg"></i>
              </button>
            </div>
          </div>
        
          <!-- 🔽 GPT Response Box -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">GPT Response</label>
            <div id="gptResult" class="p-4 bg-gray-50 border border-gray-200 rounded text-sm font-mono whitespace-pre-wrap h-48 overflow-auto"></div>
          </div>
        
        </div>

    </main>
  </div>

  <!-- Scripts -->
  <script>
    const API_KEY = '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';

  let loadedData = [];
  let tableNames = [];

  // Load table names
  $.get('/api/get-table-names', function (res) {
    if (!res.success || !Array.isArray(res.tables)) {
      $('#gptResult').text('❌ No tables found in selected database.');
      return;
    }

    tableNames = res.tables;
    res.tables.forEach(tableName => {
      $('#tableSelector').append(`<option value="${tableName}">${tableName}</option>`);
    });
  });

  // Fetch table data automatically
  function autoFetchTableData(table) {
    if (!table) return;

    $('#tableSelector').val(table);
    $('#gptResult').html(`⏳ Loading <b>${table}</b> table... Please wait.`);

    $.get(`/api/get-table-data/${table}?_=${Date.now()}`, function (res) {
      if (!res.success || !res.data || !res.data.length) {
        $('#gptResult').html(`❌ No data found in table "<b>${table}</b>".`);
        loadedData = [];
        return;
      }

      loadedData = res.data;
      $('#gptResult').html(`✅ Loaded table: <b>${table}</b> with ${loadedData.length} record(s).`);
    });
  }

  // Date resolver
  function resolveDatePhrase(text) {
    const today = new Date();
    const lower = text.toLowerCase();
    let targetDate = null;

    if (lower.includes("today")) {
      targetDate = today;
    } else if (lower.includes("yesterday")) {
      targetDate = new Date(today);
      targetDate.setDate(today.getDate() - 1);
    } else if (lower.match(/\d{4}-\d{2}-\d{2}/)) {
      return lower.match(/\d{4}-\d{2}-\d{2}/)[0];
    }

    return targetDate ? targetDate.toISOString().split('T')[0] : null;
  }

  // Ask GPT
  $('#askGPT').click(function () {
    const question = $('#userQuestion').val().trim();
    if (!question) {
      alert("Enter a question.");
      return;
    }

    if (!loadedData.length) {
      $('#gptResult').html("⏳ Loading table... Please wait.");
      return;
    }

    const tableName = $('#tableSelector').val();
    const today = new Date().toISOString().split('T')[0];
    const resolvedDate = resolveDatePhrase(question);

    const sampleData = loadedData.slice(0, 20).map(row => {
      const flat = {};
      for (let key in row) {
        if (typeof row[key] !== 'object' && row[key] !== null && row[key] !== '') {
          flat[key] = row[key];
        }
      }
      return flat;
    });

    const jsonData = JSON.stringify(sampleData, null, 2);

    const prompt = `
You are analyzing a database table named "${tableName}".

Today's date is "${today}".
If the question includes "today", treat it as "${today}".
If the question includes "yesterday", treat it as "${resolvedDate ?? '[unknown]'}".

Below is a sample of the first 20 rows (not full database):

${jsonData}

Now answer this question based only on the data above:
"${question}"
`.trim();

    $('#gptResult').html("⏳ Generating response from ChatGPT...");

    $.ajax({
      url: "https://api.openai.com/v1/chat/completions",
      method: "POST",
      headers: {
        "Authorization": `Bearer ${API_KEY}`,
        "Content-Type": "application/json"
      },
      data: JSON.stringify({
        model: "gpt-3.5-turbo",
        messages: [{ role: "user", content: prompt }]
      }),
      success: function (res) {
        $('#gptResult').html(res.choices[0].message.content);
      },
      error: function (xhr) {
        const err = xhr.responseJSON?.error;
        if (err?.code === 'context_length_exceeded') {
          $('#gptResult').html("❌ Data too large. Try reducing to 10 rows and fewer columns.");
        } else {
          $('#gptResult').html("❌ Error: " + (err?.message || "Unknown error"));
        }
      }
    });
  });

  // Enter key to trigger GPT
  $('#userQuestion').on('keydown', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
      e.preventDefault();
      $('#askGPT').click();
    }
  });

  // Auto-select & load table by keyword
  const keywordGroups = [
    { keywords: ['sale', 'sales', 'invoice', 'billing', 'order', 'retail', 'transaction', 'bill', 'so_', 'po_'], label: 'Sales' },
    { keywords: ['stitch', 'stitching', 'stich', 'stiching', 'stichings', 'Production', 'prod', 'production'], label: 'Stitching' }
  ];

  $('#userQuestion').on('input', function () {
    const question = $(this).val().toLowerCase();

    for (const group of keywordGroups) {
      const matchedKeyword = group.keywords.find(keyword => question.includes(keyword));
      if (matchedKeyword) {
        const matchedTable = tableNames.find(table =>
          table.toLowerCase().includes(matchedKeyword)
        );

        if (matchedTable) {
          autoFetchTableData(matchedTable); // Auto-load data
          break;
        }
      }
    }
  });

    const chatHistory = [];

    function addToChatSidebar(question) {
        if (!question.trim()) return;
    
        chatHistory.push(question);
    
        const listItem = `<li>
            <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded truncate" title="${question}">
              ${question}
            </a>
          </li>`;
    
        $('#chatHistory').prepend(listItem);
    }
    
    $('#askGPT').click(function () {
        const question = $('#userQuestion').val().trim();
        if (!question) return;
    
        addToChatSidebar(question);
    });
  $(document).ready(function () {  
    $('#newChatBtn').click(function () {
      $('#userQuestion').val('');
      $('#gptResult').html('');
      $('#tableSelector').val('');
      $('#chatHistory').prepend(`
        <li>
          <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">🆕 New Chat started</a>
        </li>
      `);
    });

    // Search Chat
    $('#searchChatBtn').click(function () {
      alert('Search function coming soon!');
      // You can add a modal/search box here later
    });

    // Library
    $('#libraryBtn').click(function () {
      $('#gptResult').html("📚 Your library is currently empty. Add saved prompts or documents.");
    });

    // Sora
    $('#soraBtn').click(function () {
      $('#gptResult').html("🎬 Sora is a video generation feature. Coming soon.");
    });

    // GPTs
    $('#gptsBtn').click(function () {
      $('#gptResult').html("🤖 You can explore and create custom GPTs. Feature under development.");
    });
  });
  </script>

</body>
</html>
