import java.util.ArrayList;

public interface StockMarket {


    void printAllCompanies();
    void printHistory();

    boolean isSymbolExists(String s);

    Company getCompany(String symbol);
    ArrayList<String> getSymbolHistory(String s);
}
