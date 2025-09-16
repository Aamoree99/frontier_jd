require('dotenv').config();
const { Client, GatewayIntentBits, Partials } = require('discord.js');

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMembers,
        GatewayIntentBits.GuildMessageReactions,
    ],
    partials: [Partials.Message, Partials.Channel, Partials.Reaction],
});

// Загружаем переменные из .env
const TOKEN = process.env.DISCORD_TOKEN;
const CHANNEL_ID = process.env.CHANNEL_ID;
const ROLE_ID = process.env.ROLE_ID;
const EMOJI = process.env.EMOJI;

client.once('clientReady', () => {
    console.log(`Logged in as ${client.user.tag}`);
});

client.on('messageReactionAdd', async (reaction, user) => {
    if (reaction.message.channel.id !== CHANNEL_ID) return;
    if (reaction.emoji.name !== EMOJI) return;
    if (user.bot) return;

    const guild = reaction.message.guild;
    const member = await guild.members.fetch(user.id);
    const role = guild.roles.cache.get(ROLE_ID);

    if (role && member) {
        await member.roles.add(role);
        console.log(`Added role ${role.name} to ${member.user.tag}`);
    }
});

client.on('messageReactionRemove', async (reaction, user) => {
    if (reaction.message.channel.id !== CHANNEL_ID) return;
    if (reaction.emoji.name !== EMOJI) return;
    if (user.bot) return;

    const guild = reaction.message.guild;
    const member = await guild.members.fetch(user.id);
    const role = guild.roles.cache.get(ROLE_ID);

    if (role && member) {
        await member.roles.remove(role);
        console.log(`Removed role ${role.name} from ${member.user.tag}`);
    }
});

client.login(TOKEN);
