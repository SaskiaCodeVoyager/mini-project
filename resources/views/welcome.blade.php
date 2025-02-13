<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Awesome Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="relative bg-gradient-to-r from-purple-700 via-indigo-600 to-blue-700 h-screen text-white overflow-hidden">
        <div class="relative bg-gradient-to-r from-purple-700 via-indigo-600 to-blue-700 h-screen text-white overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('images/background.jpg') }}" 
                     alt="Background Image" 
                     class="object-cover object-center w-full h-full brightness-75" />
            </div>
        
        <div class="relative z-10 flex flex-col justify-center items-center h-full text-center">
            <h1 class="text-6xl font-extrabold leading-tight mb-4 animate-fade-in bg-gradient-to-b from-white to-blue-300 bg-clip-text text-transparent">
                PKL HUMMATECH
            </h1>            
            <p class="text-xl text-gray-300 mb-6 animate-slide-in">
                jika anda stres magang di hummatech maka saya juga sama stresnya<br>
                AYOO PINDAH!!! 
            </p>
           

            <a href="{{ route('login') }}" id="animatedButton" 
            class="flex items-center px-6 py-3 rounded-full text-white text-lg font-semibold 
                    bg-gradient-to-r from-blue-500 to-blue-700 shadow-lg hover:scale-105 transform transition duration-300 h-16 relative overflow-hidden">
                <div id="ball" 
                    class="w-16 h-16 flex items-center justify-center bg-white rounded-full shadow-md absolute left-[1px] left-[-10px] transition-all duration-500 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                        class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 3l9 6.75M5.25 10.5v9.75h4.5v-6h4.5v6h4.5V10.5"/>
                    </svg>
                </div>
                <span id="buttonText" class="ml-12">Mulai</span>
            </a>

<script>
    document.getElementById("animatedButton").addEventListener("click", function () {
        let ball = document.getElementById("ball");
        let buttonText = document.getElementById("buttonText");

        // Bola bergerak ke kanan
        ball.classList.remove("left-[-10px]");
        ball.classList.add("left-[calc(100%-4rem)]");

        // Teks bergerak ke kiri
        buttonText.classList.remove("ml-12");
        buttonText.classList.add("mr-12");

        // Setelah 1 detik, bola bergerak kembali ke kiri dan teks kembali ke posisi semula
        setTimeout(function () {
            ball.classList.remove("left-[calc(100%-4rem)]");
            ball.classList.add("left-[-10px]");

            buttonText.classList.remove("mr-12");
            buttonText.classList.add("ml-12");
        }, 1000); // Menunggu 1 detik sebelum bergerak kembali ke kiri
    });
</script>





         
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slide-in {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in { animation: fade-in 1s ease-out; }
        .animate-slide-in { animation: slide-in 1.2s ease-out; }
    </style>
</body>
</html>
