<div id="admin-greeting" class="relative p-4 mb-4 text-white bg-green-500 rounded-lg shadow-lg">
    <button onclick="this.parentElement.style.display='none'" class="absolute top-0 right-0 p-2 text-lg font-bold">&times;</button>
    
    <p class="text-lg font-semibold">
        <span id="greeting"></span>, <strong>{{ Auth::user()->nom_complet ?? Auth::user()->name }}</strong> 
    </p>
    
    <p class="mt-1">Nous sommes le <span id="current-date" class="font-medium"></span>. Voici un aperçu de votre journée.</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const greetingElement = document.getElementById('greeting');
        const adminGreeting = document.getElementById('admin-greeting');
        const currentHour = new Date().getHours();

        if (currentHour < 12) {
            greetingElement.textContent = 'Bonjour';
        } else if (currentHour < 18) {
            greetingElement.textContent = 'Bon après-midi';
        } else {
            greetingElement.textContent = 'Bonsoir';
        }

        //Gestion de la date du jour en français
        const currentDateElement = document.getElementById('current-date');

        // On vérifie d'abord si l'élément existe bien dans la page avant de remplir
        if (currentDateElement) {
            const currentDate = new Date().toLocaleDateString('fr-FR', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
    
            // Ceci pour mettre la première lettre en majuscule (ex: Mercredi au lieu de mercredi)
            currentDateElement.textContent = currentDate.charAt(0).toUpperCase() + currentDate.slice(1);
        }


        setTimeout(() => {
            if (adminGreeting) {
                adminGreeting.style.transition = 'opacity 1s ease-out';
                adminGreeting.style.opacity = '0';
                setTimeout(() => adminGreeting.style.display = 'none', 1000);
            }
        }, 30000);
    });
</script>
