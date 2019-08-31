import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.Socket;
import javax.swing.*;

class ConnectionServer implements Runnable, ActionListener, ClientServer {

    String clientName = null;
    String clientSymbol = null;

    private Socket mySocket;

    private int currentState;
    private double currentBid;

    private Server mainServer;
    private Company currentCompany = null;

    private static StockDatabase stock = null;
    private BufferedReader in = null;
    private PrintWriter out = null;

    private Thread t;

    public ConnectionServer(Server mainServer, StockDatabase stock) {
        this.mySocket = null;
        this.currentState = WAIT_NAME;
        this.clientName = "User";
        this.clientSymbol = null;
        this.mainServer = mainServer;
        this.stock = stock;

        Timer timer = new Timer(500, this);
        timer.start();
    }

    @Override
    public void actionPerformed(ActionEvent e) {

        if (currentState == ALLOW_BID) {

            // Show if the value is changed and it was done by other user
            if (currentBid != currentCompany.getPrice() && clientName.compareTo(currentCompany.getBidBy()) != 0) {
                currentBid = stock.getPriceOnCompany(clientSymbol);

                // TODO: Need to post this message to the client
                mainServer.postMSG(">> " + currentBid + " by " + currentCompany.getBidBy());
            }
        }
    }

    public boolean handleConnection(Socket socket) {
        this.mySocket = socket;
        t = new Thread(this);
        t.start();
        return true;
    }

    @Override
    public void run() {
        try {
            in = new BufferedReader(new InputStreamReader(mySocket.getInputStream()));
            out = new PrintWriter(new OutputStreamWriter(mySocket.getOutputStream()));
            String line, reply;

            for (line = in.readLine();
                 line != null && !line.equals("quit");
                 line = in.readLine()) {

                switch (currentState) {
                    case WAIT_NAME:
                        if (line.length() != 0) {
                            clientName = line;
                            currentState = WAIT_SYMBOL;
                            reply = "Enter the Symbol of the company you wish to bid:\n";

                        } else {
                            reply = "Please enter an non-empty string as your name:\n";
                        }
                        break;

                    case WAIT_SYMBOL:
                        clientSymbol = line.trim();

                        if (authSymbol(clientSymbol)) {

                            currentCompany = stock.getCompany(clientSymbol);
                            double price = currentCompany.getPrice();

                            reply = "You are now able to bid for " + clientSymbol + "\n";
                            reply += "Current stock price is " + price + "\n";

                            currentBid = price;
                            currentState = ALLOW_BID;

                        } else {
                            reply = "-1\n";
                        }

                        mainServer.postMSG(this.clientName + " > " + line);
                        break;

                    case ALLOW_BID:
                        try {
                            double bidValue = Double.parseDouble(line);
                            reply = newBid(bidValue);

                        } catch (Exception ex) {
                            reply = "Please enter a valid amount as the bid\n";
                        }
                        break;

                    default:
                        System.out.println("Undefined state");
                        reply = "Undefined state\n";
                        return;
                }
                out.print(reply);
                out.flush();

                //t.sleep(500);
            }
            out.close();
            in.close();
            this.mySocket.close();

        } catch (IOException e) {
            System.out.println(e);
        }
    }

    private String newBid(double bidValue) {
        String reply;

        if (bidValue > currentCompany.getPrice()) {

            stock.newBidEntry(clientSymbol, clientName, bidValue);
            stock.newHistoryRecord(clientSymbol, bidValue, clientName);

            mainServer.postMSG(this.clientName + " > " + clientSymbol + " > " + bidValue);
            reply = "Your bid, " + bidValue + " is accepted for " + currentCompany.getName() + "\n";

        } else {
            reply = "Your bid is less than current highest bid, " + currentCompany.getPrice() + " for " + currentCompany.getName();
            reply += ". Please try with a higher amount.\n";
        }

        return reply;

    }

    public boolean authSymbol(String symbol) {
        return stock.isSymbolExists(symbol);
    }
}

    
    

